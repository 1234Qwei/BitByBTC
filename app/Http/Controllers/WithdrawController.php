<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WithdrawRequest;
use App\Models\WithdrawRequestTemp;
use App\Models\ExchangeSwap;
use App\Models\Coin;
use App\Models\Bank;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Auth;

class WithdrawController extends Controller
{
	private $model;
	private $data;

	public function __construct()
	{
		$this->model = new WithdrawRequest();
		$this->swapModel = new ExchangeSwap();
	}

	public function getWithdraw(Request $request)
	{
		$this->data['pageName'] = 'withdraw';
		if ($request->segment(2) !== null) {
			try {
				$data = Crypt::decrypt($request->segment(2));
				$coins = Coin::where('coin_id', $data['coin_id'])->first();
				$coin = Coin::find($coins->id);
				$this->data['coin'] = $coin;
				if ($data['id'] != 0) {
					$this->data['withdraw'] = $this->model->find($data['id']);
					if (!$this->data['withdraw']) return redirect('withdraw-request');

					$data['user_id'] = $this->data['withdraw']->user_id;
				}
				$this->data['banks'] = Bank::where('status', 1)->where('user_id', $data['user_id'])->get();
				$this->data['balanceAmount'] = $this->swapModel->availbleCoinBalance($data['user_id'], $coin->coin_id);
				return view('withdraw.withdraw', ['data' => $this->data, 'isEdit' => $data['id'] ?? false]);
			} catch (DecryptException $e) {
				return redirect('withdraw-request');
			}
		} else {
			return redirect('withdraw-request');
		}
	}

	public function postWithdraw(Request $request)
	{
		$dt = Carbon::now();
		$user_id = Auth::user()->id;
		if ($request->id) {
			$withdraw = $this->model->find($request->id);
			$withdraw->transaction_hash = $request->transaction_hash;
			$withdraw->status = $request->status;
			if ($request->bank_proof) {
				$depositProofName = $withdraw->user_id . '_admin_' . $request->id . '.' . $request->bank_proof->extension();
				$request->bank_proof->move(storage_path('bank-proof'), $depositProofName);
				$withdraw->bank_proof = $depositProofName;
			}
			if ($withdraw->save() &&  $request->status == '2') {
				// create fee record
				$this->createFeeRecord([
					'is_swap_or_withdraw' => 2,
					'swap_id' => 0,
					'withdraw_id' => $withdraw->id,
					'user_id' => $withdraw->user_id,
					'coin_id' => $withdraw->coin_id,
					'fee' => $withdraw->fee_amount
				]);

				session()->flash('message', 'Your withdraw request is status updated successfully!');
			}
		} else {
			$withdrawTemp = $this->checkOTP($request);
			if ($withdrawTemp) {
				$balanceAmount = $this->swapModel->availbleCoinBalance($user_id, $withdrawTemp->coin);
				if ($withdrawTemp->amount <= $balanceAmount) {
					$withdraw = $this->model->create([
						'amount' => $withdrawTemp->amount,
						'final_amount' => $withdrawTemp->final_amount,
						'fee_amount' => $withdrawTemp->fee_amount,
						'is_withdraw_fee_checked' => $withdrawTemp->is_withdraw_fee_checked,
						'coin_id' => $withdrawTemp->coin_id,
						'coin' => $withdrawTemp->coin,
						'bank_id' => $withdrawTemp->bank_id,
						'wallet_address' => $withdrawTemp->wallet_address,
						'user_id' => $user_id,
						'remarks' => $withdrawTemp->remarks,
						'created_by' => $user_id,
						'withdrawal_date' => $dt->toDateString()
					]);

					if ($withdraw->fresh()) {
						return response()->json(['status' => true, 'message' => 'Your withdraw request is created successfully!']);
					}
				} else {
					return response()->json(['status' => false, 'message' => 'Your balance is too low!']);
				}
			} else {
				return response()->json(['status' => false, 'message' => 'Invalid OTP! Please try again!']);
			}
		}

		return redirect('withdraw-request');
	}

	public function getWithdrawRequest(Request $request)
	{
		$this->data['pageName'] = 'withdraw request';
		$this->data['withdraws'] = $this->model->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(5);

		return view('withdraw.withdraw-request', ['data' => $this->data]);
	}

	/**
	 *  send an mail OTP
	 *  
	 * @param $address String
	 * @param $amount String
	 * @param $remarks String
	 * @return JSON Boolean
	 */
	public function sendOtp(Request $request)
	{
		$dt = Carbon::now();
		$user_id = auth()->user()->id;
		// random code generation
		$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567891011121314151617181920212223242526';
		$shuffled = str_shuffle($str);
		$code = substr($shuffled, 1, 8);

		$balanceAmount = $this->swapModel->availbleCoinBalance($user_id, $request->input('coin'));
		if ($request->input('amount') <= $balanceAmount) {
			// fees calculation and generate final amount
			$feeCalculation = $this->feeCalculation($request->input('coin_id'), $request->input('amount'));

			$withdraw_tmp                  = new WithdrawRequestTemp();
			$withdraw_tmp->amount 		   = $request->input('amount');
			$withdraw_tmp->final_amount    = $feeCalculation['finalAmount'];
			$withdraw_tmp->fee_amount      = $feeCalculation['withdraw_fee'];
			$withdraw_tmp->coin 		   = $request->input('coin');
			$withdraw_tmp->is_withdraw_fee_checked = $request->input('is_withdraw_fee_checked') ?? 0;
			$withdraw_tmp->coin_id 		   = $request->input('coin_id');
			$withdraw_tmp->wallet_address  = $request->input('walletaddress');
			$withdraw_tmp->user_id 		   = $user_id;
			$withdraw_tmp->remarks 		   = $request->input('remarks');
			$withdraw_tmp->bank_id 		   = $request->input('bank_id');
			$withdraw_tmp->created_by 	   = $user_id;
			$withdraw_tmp->withdrawal_date = $dt->toDateString();
			$withdraw_tmp->otp             = $code;

			if ($withdraw_tmp->save()) {
				$coin = Coin::find($request->input('coin_id'));
				$data = [
					'otp'           => $code,
					'amount' 	    => $request->amount . ' ' . $coin->symbol,
					'final_amount'  => $feeCalculation['finalAmount'] . ' ' . $coin->symbol,
					'fee_amount'    => $feeCalculation['withdraw_fee'] . ' ' . $coin->symbol,
					'address'       => $request->walletaddress,
					'remarks'       => $request->remarks,
					'created_at'    => $withdraw_tmp->created_at,
					'email' 		=> auth()->user()->email
				];

				\Mail::send('emails.otp', $data, function ($message) use ($data) {
					$message->from(env('MAIL_FROM_ADDRESS'), 'Anyswap');
					$message->subject('【ANYSWAP】 OTP for withdraw');
					$message->to(strtolower($data['email']));
				});

				return response()->json(['status' => true, 'dataid' => $withdraw_tmp->id]);
			}
		} else {
			return response()->json(['status' => false, 'message' => 'Your balance is too low!']);
		}

		return response()->json(['status' => false]);
	}

	/**
	 * fee calculations
	 * 
	 * @param $coin String value
	 * @param $amount Float value
	 * @return withdraw fees and final amount <Array>
	 **/
	protected function feeCalculation($coin, $amount)
	{
		$coin = Coin::find($coin);
		$finalAmount = $amount - $coin->withdraw_fee;
		return ['withdraw_fee' => $coin->withdraw_fee, 'finalAmount' => $finalAmount];
	}

	/**
	 *  check OTP
	 *  
	 * @param $id  Integer
	 * @param $otp String
	 * @return Boolean
	 */
	private function checkOTP($request)
	{
		$withdraw_tmp =  WithdrawRequestTemp::where(function ($query) use ($request) {
			$query->where('id', $request->dataid)->where('otp', '=', $request->otp);
		})->first();

		if (!is_null($withdraw_tmp)) {
			$time = \Carbon\Carbon::now();
			$creat_time = $withdraw_tmp['created_at']->addMinutes(15);
			$withdrawTemp = $withdraw_tmp;
			// $withdraw_tmp->delete();
			if ($time->gt($creat_time)) {
				return false;
			}
			return $withdrawTemp;
		}

		return false;
	}
}
