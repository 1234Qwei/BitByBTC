<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stacking\StackingContract;
use App\Models\Stacking\TransactionHistory;
use App\Models\Stacking\BonusAmount;
use App\Models\Stacking\Packages;
use App\Models\Stacking\PackageTerm;
use App\Models\WithdrawRequest;
use App\Models\Coin;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class StackingContractController extends Controller
{
	private $model; 
	private $data; 

	public function __construct()
	{
		$this->model = new StackingContract();
		$this->withdraw = new WithdrawRequest();
	}
 
	public function getStacking(Request $request) 
	{
		$this->data['pageName'] = 'Stacking contract'; 
		$this->data['stackingCoin'] = Coin::where('status', 1)->where('is_stacking', 0)->where('symbol', '<>', 'INR')->orderBy('sort_order', 'ASC')->get();
		// $stacking_currency = 8;
		$this->data['stacking_currency'] = Coin::where('coin_id')->first();
		$this->data['stacking_symbol'] = 'SAFEMOON';
		$this->data['packages'] = Packages::orderByRaw("CAST(coin  as UNSIGNED) ASC")->get();
		$this->data['packageTerms'] = PackageTerm::where('asset_id', 9)->get();
		return view('stacking.index', ['data' => $this->data]);
	}

	public function postStacking(Request $request)
	{
		$dt = Carbon::now(); 
		$packageTerm = PackageTerm::find($request->input('term'));
		$durarion = $packageTerm->duration ?? 6;

		$stacking = $this->model->create([
			'package_id' => $request->input('package'),
			'asset_id' => $request->input('stacking_currency') ?? 9,
			'user_id' => auth()->user()->id,
			'term_id' => $request->input('term'),
			'billing_no' => $this->billNumber(), 
			'date' => $dt->toDateString(),
			'expiry_date' => $request->input('term') ? $dt->addMonths($durarion)->toDateString() : 0
		]);

		if ($stacking->fresh()) {
			session()->flash('message', 'Your stacking contract is created successfully!');
		}
		if ($request->is_existing) {
			$this->gettingFromDepoist($stacking->id);
			return redirect('contract');
		} else {
			return redirect('stacking-address/' . Crypt::encryptString($stacking->fresh()->id));
		}
	}

	private function billNumber()
	{
		$latest = $this->model->latest()->first();

		if (!$latest) {
			return 'ANYSWAP00050001';
		}

		$string = preg_replace("/[^0-9\.]/", '', $latest->billing_no);

		return 'ANYSWAP' . sprintf('%08d', $string + 1);
	}
/////////////////////
	public function getContractAddress(Request $request)
	{
		if ($request->segment(2) !== null) { 
			try {
				$id = Crypt::decryptString($request->segment(2));
				$stacking = $this->model->find($id);
				$this->data['stacking'] = $stacking;
				$coin = Coin::find($stacking->asset_id);
				$this->data['symbol'] = $coin->symbol;
				$this->data['wallet'] = $coin->stacking_address ?? $coin->address;
				$this->data['duration'] = $coin->stacking_address;
				$this->data['pageName'] = 'Stacking contract address';
				return view('stacking.index-address', ['data' => $this->data]);
			} catch (DecryptException $e) {
				return redirect('contract');
			}
		} else {
			return redirect('contract');
		}
	}

	public function getContract(Request $request)
	{
		$this->data['pageName'] = 'Contract';
		$userId = auth()->user()->id;
		if ($userId === 1) {
			$this->data['contracts'] = StackingContract::orderBy('id', 'ASC')->paginate(10);
		} else {
			$this->data['contracts'] = StackingContract::where('user_id', $userId)->orderBy('id', 'DESC')->paginate(10);
		}
		return view('stacking.contract', ['data' => $this->data]);
	}

	public function viewContract(Request $request)
	{
		if ($request->segment(2) !== null) {
			try {
				$this->data['stacking'] = $this->model->find($request->segment(2));
				$this->data['pageName'] = 'Stacking contract';
				return view('stacking.contract-update', ['data' => $this->data]);
			} catch (DecryptException $e) {
				return redirect('contract');
			}
		} else {
			return redirect('contract');
		}
	}

	public function postContract(Request $request)
	{
		$dt = Carbon::now();
		$stacking = $this->model->find($request->id);
		$stacking->status = $request->status;
		$stacking->approved_date = $dt->toDateString();
		$stacking->expiry_date = ($stacking->type == '1') ? $dt->addMonths(20)->toDateString() : $dt->addMonths(20)->toDateString();

		if ($stacking->save()) {
			session()->flash('message', 'This stacking contract is staus is updated successfully!');
		}

		return redirect('contract');
	}
 
	public function getBonus(Request $request) 
	{
		$this->data['pageName'] = 'bonus amount';
		$userId = auth()->user()->id;
		if ($userId === 1) {
			$this->data['bonus'] = BonusAmount::where('status', 1)->orderBy('id', 'DESC')->paginate(10);
		} else {
			$this->data['bonus'] = BonusAmount::where('user_id', $userId)->where('status', 1)->orderBy('id', 'DESC')->paginate(10);
		}
		return view('stacking.bonus', ['data' => $this->data]);
	}

	public function getTransacion(Request $request)
	{
		$this->data['pageName'] = 'transaction History';
		$userId = auth()->user()->id;
		if ($userId === 1) {
			$this->data['transactionhistorys'] = TransactionHistory::orderBy('id', 'DESC')->paginate(10);
		} else {
			$this->data['transactionhistorys'] = TransactionHistory::where('user_id', $userId)->orderBy('id', 'DESC')->paginate(10);
		}
		return view('stacking.transacion', ['data' => $this->data]);
	}

	/**
	 * @param ajax input request
	 * @return HTML file for stacking <JSON>
	 **/
	public function getStackingView(Request $request)
	{
		if ($request->id) { 
			$stacking = StackingContract::find($request->id); 
			$template = 'modal.stacking-view';

			$returnHTML = view($template, [
				'stacking' => $stacking,
			])->render();

			return response()->json(['success' => true, 'html' => $returnHTML]);
		} else {
			return response()->json(['Error' => false]);
		}
	} 

	/**
	 * @param Form input request
	 * @return update to stacking table
	 **/
	public function postStackingApprove(Request $request)
	{
		$stacking = StackingContract::find($request->id);
		$userId = auth()->user()->id;

		if ($userId !== 1) {
			$checkExists = StackingContract::where('transaction_id', $request->transaction_id)->first();
			if ($checkExists && auth()->user()->id !== 1) {
				session()->flash('error', 'Your transaction id is not valid / duplicate!');
				return redirect('contract');
			}
			$stacking->transaction_id = $request->transaction_id ?? null;
		} else {
			$dt = Carbon::now();
			$packageTerm = PackageTerm::find($stacking->term_id);
			$durarion = $packageTerm->duration ?? 6;

			$stacking->approved_date = date('Y-m-d h:m:s');
			$stacking->status = $request->status ?? 0;
			$stacking->approver_comment = $request->approver_comment;
			$stacking->approved_by = auth()->user()->id;
			$bonusAmount = ((((int) $stacking->package->coin) * $stacking->term->interest) / 100) / 30;
			$stacking->daily_bonus = $bonusAmount;
			$stacking->expiry_date = $dt->addMonths($durarion)->toDateString();
		}

		if ($stacking->save()) {
			$this->sendStackingEmail($request->id);
			session()->flash('message', 'Your request was updated successfully!');
		}

		return redirect('contract');
	}

	public function sendStackingEmail($id)
	{
		$stacking = StackingContract::find($id);
		$data = [];
		$data = [
			'user' => $stacking->user->name . '( ' . $stacking->user->username . ')',
			'coin' => $stacking->package->asset->coin,
			'stacking_amount' => $stacking->package->coin . ' ' . $stacking->package->asset->symbol,
			'stacking_term' => $stacking->term->duration . ' Month (' . $stacking->term->interest . ' %)',
			'created_at' => $stacking->created_at,
			'approver_comment' => $stacking->approver_comment,
			'approved_date' => $stacking->approved_date,
			'email' => $stacking->user->email,
			'status' => $stacking->status
		];
		$template = 'emails.stacking';
		$data['subject'] = '【ANYSWAP】Request for Stacking';

		\Mail::send($template, $data, function ($message) use ($data) {
			$message->from(env('MAIL_FROM_ADDRESS'), 'AnySwap');
			$message->subject($data['subject']);
			$message->to(strtolower($data['email']));
			$message->bcc(['anyswapex@gmail.com', 'bigkkumar@gmail.com', 'bitcoinsanjay001@gmail.com', 'asangarch@gmail.com', 'anu.ma1987@gmail.com']);
		});
	}

	private function gettingFromDeposit($id)
	{
		$userId = auth()->user()->id;
		$stacking = StackingContract::find($id);
		$dt = Carbon::now();
		$packageTerm = PackageTerm::find($stacking->term_id);
		$durarion = $packageTerm->duration;
		$stacking->approved_date = date('Y-m-d h:m:s');
		$stacking->status = 1;
		$stacking->approver_comment = 'Re-stacking getting auto approved!';
		$stacking->approved_by = $userId;
		$bonusAmount = ((((int) $stacking->package->coin) * $stacking->term->interest) / 100) / 30;
		$stacking->daily_bonus = $bonusAmount;
		$stacking->expiry_date = $dt->addMonths($durarion)->toDateString();

		if ($stacking->save()) {
			$data = [
				'user_id' => $userId,
				'amount' => $stacking->package->coin,
				'coin_id' => $stacking->package->asset->id,
				'coin' => $stacking->package->asset->coin_id,
				'stacking_id' => $stacking->id,
			];
			$this->reduceDepositAmount($data);
		}
	}

	private function reduceDepositAmount($data)
	{ 
		$dt = Carbon::now();
		$withdraw = $this->withdraw->create([
			'amount' => $data['amount'],
			'final_amount' => $data['amount'],
			'fee_amount' => 0.00,
			'is_withdraw_fee_checked' => 0,
			'coin_id' => $data['coin_id'],
			'coin' => $data['coin'],
			'bank_id' => NULL,
			'wallet_address' => NULL,
			'user_id' => $data['user_id'],
			'remarks' => 'Amount debited for stacking',
			'created_by' => $data['user_id'],
			'withdrawal_date' => $dt->toDateString()
		]);

		if ($withdraw) {
			$withdraw = $this->withdraw->find($withdraw->id);
			$withdraw->stacking_id = $data['stacking_id'];
			$withdraw->status = 2;
			if ($withdraw->save()) {
				$this->sendStackingEmail($data['stacking_id']);
				session()->flash('message', 'Your request was updated successfully!');
			}
		}
	}

	public function deleteStacking(Request $request)
	{
		$id = $request->id;
		if ($id) {
			$stacking = StackingContract::find($id);
			if ($stacking->delete()) {
				return response()->json(['success' => true]);
			}
		}
		return response()->json(['success' => false]);
	}
}
