<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bank;
use App\Models\BankMaster;
use App\Models\Coin;
use App\Models\LoggedInHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
	protected $data;
	protected $isAdmin;
	protected $userId;
	protected $userName;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
			$user =
				Auth::user();
			$this->userId = $user->id;
			$this->userName = $user->username;
			$this->isAdmin = ($user->role === 1) ? true : false;
			return $next($request);
		});
	}

	public function getChangePassword()
	{
		$this->data['pageName'] = 'change password';
		$this->data['user'] = auth()->user();
		$this->data['loggedInHistory'] = LoggedInHistory::where('user_id', $this->userId)->orderBy('id', 'desc')->limit(5)->get();
		return view('account.change-password', ['data' => $this->data]);
	}
	public function commonotpGenerate(Request $request)
	{
		$user = Auth::user();
		$controller = new LoginController;
		if ($request->action == 'generateOtp') {
			return $controller->sendotptoMail($user->id, $user->email, 'sell', 'ajax');
		} elseif ($request->action == 'VerifyOtp') {
			return $controller->checkotpVerification($user->id, $request->emailotp);
		} elseif ($request->action == 'reSend') {
			$request->request->add(['user_id' => $user->id]);
			return $controller->resendOtp($request);
		}
	}
	public function postChangePassword(Request $request)
	{
		$request->validate([
			'oldpassword' => 'required',
			'password' => 'required|min:8',
			'confirm_password' => 'required',
		]);

		$user = Auth::user();
		if (\Hash::check($request->input('oldpassword'), $user->password)) {
			$user->password = bcrypt($request->input('password'));
			if ($user->save()) {
				session()->flash('success', 'Your password is updated successfully!');
			}
		} else {
			session()->flash('error', 'Your current password is invalid!');
		}

		return redirect('change-password');
	}

	public function getUsers(Request $request)
	{
		return redirect('account');
		$this->data['pageName'] = 'Users';
		$users = User::where('id', '<>', 1);
		$this->data['status'] = [
			'0' => 'Not Verified',
			'1' => 'In Active',
			'2' => 'Verified'
		];
		if ($request->has('search') && $request->get('search') != '') {
			$keyword = $request->get('search');
			$users->where(function ($query) use ($keyword) {
				$query->where('name', 'LIKE', '%' . $keyword . '%')
					->orWhere('username', 'LIKE', '%' . $keyword . '%')
					->orWhere('email', 'LIKE', '%' . $keyword . '%')
					->orWhere('mobile', 'LIKE', '%' . $keyword . '%');
			});
		}

		if ($request->has('status') && $request->get('status') != '') {
			$users->where('status', $request->status);
		}

		$this->data['users'] = $users->orderBy('id', 'DESC')->paginate(10);
		return view('users', ['data' => $this->data]);
	}

	public function getUser(Request $request, $id = 0)
	{
		if (!$this->isAdmin) {
			$id = $this->userId;
		}

		$this->data['pageName'] = 'Profile';
		if ($id) {
			$this->data['user'] = User::find($id);
		} else {
			return redirect('users');
		}
		$coins = Coin::where('status', 1)->orderBy('sort_order', 'ASC')->get();
		$this->data['coins'] = $coins;
		$this->data['referralCount'] = User::where('referred_by', auth()->user()->username)->where('status', 2)->count();
		return view('users-form', ['data' => $this->data]);
	}

	public function postUser(Request $request)
	{
		$id = $request->get('id');
		$validate = [
			'name' => 'required',
			'username' => 'unique:users,username,' . $id,
			'mobile' => Rule::unique('users', 'mobile')->where(function ($query) use ($id) {
				return $query->where('id', '<>', $id)->whereNotNull('mobile');
			})
		];
		if ($this->isAdmin) $validate['email'] = 'required|unique:users,email,' . $id;
		$request->validate($validate);

		$user = User::find($id);
		if ($user) {
			$user->name = $request->name;
			$user->email = $request->email ?? $user->email;
			$user->status = $request->status ?? $user->status;
			$user->mobile = $request->mobile;
			if ($request->password) {
				$user->password = bcrypt($request->input('password'));
			}
			if ($user->save()) {
				if ($this->isAdmin && ($request->exitingEmail !== $request->email)) {
					$user->is_email_verified = 0;
					$user->save();
					$data = [
						'url' => url('email-verification') . '/' . Crypt::encryptString($user->id),
						'name' => $user->name,
						'email' => $user->email
					];

					\Mail::send('emails.verification', $data, function ($message) use ($data) {
						$message->from(env('MAIL_FROM_ADDRESS'), 'AnySwap');
						$message->subject('【ANYSWAP】 Confirm Your Email');
						$message->to(strtolower($data['email']));
					});
				}

				session()->flash('message', 'User profile is updated successfully!');
			} else {
				session()->flash('error', 'Something went wrong!');
			}
		}

		return redirect($this->isAdmin ? 'users' : 'user');
	}

	public function getBank(Request $request)  
	{
		$this->data['pageName'] = 'Bank Settings';
		$id = $request->id ?? 0;
		$this->data['bank'] = Bank::findorNew($id);

		$this->data['bankMaster'] = BankMaster::where('status', 1)->get();

		return view('bank.form', ['data' => $this->data]);
	}

	public function getBanks(Request $request)
	{
		$this->data['pageName'] = 'Bank Settings';
		if ($this->isAdmin) {
			$this->data['bank'] = Bank::orderBy('id', 'DESC')->paginate(10);
		} else {
			$this->data['bank'] = Bank::where('user_id', $this->userId)->orderBy('id', 'DESC')->paginate(10);
		} 

		return view('bank.list', ['data' => $this->data]);
	}

	public function postBank(Request $request) 
	{
		if ($request->selected_account === '1') {
			$request->validate([
				'selected_account' => 'required',
				'ifsc_code' => 'required',
				'account_number' => 'required',
				'bank_name' => 'required',
			]);
		} else {
			$request->validate([
				'selected_account' => 'required',
				'upi' => 'required'
			]);
		}

		$id = $request->id ?? 0;
		$checkBankAccountType = Bank::where('selected_account', $request->selected_account)->where('user_id', $this->userId)->first();

		if (!$id && $checkBankAccountType) {
			session()->flash('error', 'You have already have the same account type!');
			return redirect('banks');
		}
		$bank = Bank::findOrCreate($id);
		$bank->selected_account = $request->selected_account;
		$bank->bank_name = $request->bank_name;
		$bank->user_id = ($this->isAdmin && $id) ? $bank->user_id : $this->userId;
		$bank->ifsc_code = $request->ifsc_code;
		$bank->account_number = $request->account_number;
		$bank->upi = $request->upi;
		$bank->account_type = $request->account_type;

		if ($request->status) {
			$bank->status = $request->status;
			$bank->approved_by = $this->userId;
		}

		if ($request->is_primary == '1') {
			Bank::where('user_id', $this->userId)->update(['is_primary' => 0]);
			$bank->is_primary = $request->is_primary ?? 0;
		}

		if ($bank->save()) {
			session()->flash('success', 'User profile is updated successfully!');
		} else {
			session()->flash('error', 'Something went wrong!');
		}

		return redirect('banks');
	}

	public function getReferral(Request $request)
	{
		$this->data['pageName'] = 'Referral';
		if ($this->isAdmin) {
			$users = User::whereNotNull('referred_by');
		} else {
			$users = User::where('referred_by', $this->userName);
		}

		$this->data['status'] = [
			'0' => 'Not Verified',
			'1' => 'In Active',
			'2' => 'Verified'
		];

		if ($request->has('search') && $request->get('search') != '') {
			$keyword = $request->get('search');
			$users->where(function ($query) use ($keyword) {
				$query->where('name', 'LIKE', '%' . $keyword . '%')
					->orWhere('username', 'LIKE', '%' . $keyword . '%')
					->orWhere('email', 'LIKE', '%' . $keyword . '%')
					->orWhere('mobile', 'LIKE', '%' . $keyword . '%');
			});
		}

		if ($request->has('status') && $request->get('status') != '') {
			$users->where('status', $request->status);
		}

		$this->data['users'] = $users->orderBy('id', 'DESC')->paginate(10);
		return view('referral', ['data' => $this->data]);
	}
}
