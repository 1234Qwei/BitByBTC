<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Coin;
use App\Models\Settings;
use App\Models\ExchangeDeposit;
use App\Models\ExchangeSwap;
use App\Models\SellOrder;
use App\Models\UserBank;
use App\Models\User;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Session;
use DateTime;

class ExchangeController extends Controller
{
    protected $data;
    protected $model;
    protected $commision = 2;
    protected $inrToCoinPercentage = 8; // inr to coin
    protected $inrToCoinPercentageOpt = 1; // inr to coin option
    protected $coinToInrPercentage = 3; // coin to inr
    protected $coinToInrPercentageOpt = 1; // coin to inr option
    protected $coinToCoinPercentage = 4; // coin to coin
    protected $coinToCoinPercentageOpt = 1; // coin to coin option

    public function __construct()
    {
        $this->middleware('auth');
        $this->depositModel = new ExchangeDeposit();
        $this->swapModel = new ExchangeSwap();
        $this->commision = Settings::getKeyValue('swap_fee_commision');
    }

    public function index(Request $request)
    {
        $this->data['pageName'] = 'Exchange';
        $this->data['wallet'] = '';
        $this->data['depositcoin'] = Coin::where('status', 1)->where('exchange_type', 1)->orderBy('sort_order', 'ASC')->get();
        $this->data['swapcoin'] = Coin::where('status', 1)->orderBy('sort_order', 'ASC')->get();
        $this->data['banks'] = Bank::where('status', 1)->where('user_id', 1)->orderBy('selected_account', 'ASC')->get();
        $this->data['availableCoin'] = $this->depositModel->availbleCoinBalance(auth()->user()->id);
        $this->data['commision'] = $this->commision;
        return view('exchange', ['data' => $this->data]);
    }

    /**
     * @return deposit list HTML view
     **/
    public function getDeposits(Request $request)
    {
        $this->data['pageName'] = 'Deposit';
        $this->data['status'] = [
            '3' => 'Pending',
            '0' => 'Not Verified',
            '1' => 'Approved',
            '2' => 'Rejected'
        ];
        $exchange = $this->depositModel->with('user', 'deposit');

        $exchange = $exchange->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->paginate(10);
        $this->data['exchange'] = $exchange;
        return view('exchange-deposit-list', ['data' => $this->data]);
    }


    public function postExchange(Request $request)
    {
        $isSuccess = false;
        $redirectPage = 'deposit';
        if ($request->exchange_type === '1') {
            $request->validate([
                'transaction_id' => Rule::unique('exchange_deposit', 'transaction_id')->where(function ($query) {
                    return $query->whereNotNull('transaction_id');
                }),
            ]);

            $exchange = new ExchangeDeposit(); 
            $exchange->user_id = auth()->user()->id;
            $exchange->exchange_type = $request->exchange_type;
            $exchange->bank_id = $request->bank_id ?? null;
            $exchange->transaction_id = $request->transaction_id ?? null;
            $exchange->deposit_coin = $request->deposit_coin;
            $exchange->deposit_currency = $request->deposit_currency ?? null;
            if ($exchange->save()) {
                $isSuccess = true;
                $exchangeId = $exchange->fresh()->id;
            }
        }

        if ($isSuccess) {
            $this->sendEmail($exchangeId, $request->exchange_type);
            session()->flash('message', 'Your request was created successfully!');
        } else {
            session()->flash('error', 'Something went wrong! Please try again after sometime!');
        }

        return redirect($redirectPage);
    }

    /**
     * @param Form input request
     * @return update to the exchage deposit / swap table
     **/
    public function postExchangeApprove(Request $request)
    {
        if ($request->exchange_type == '1') {
            $exchange = ExchangeDeposit::find($request->id);
            $redirect = 'deposit';
            if ($request->bank_proof) {
                $depositProofName = $exchange->user_id . '_' . $request->id . '.' . $request->bank_proof->extension();
                $request->bank_proof->move(storage_path('bank-proof'), $depositProofName);
                $exchange->bank_proof = $depositProofName;
            } else {
                $checkExists = ExchangeDeposit::where('transaction_id', $request->transaction_id)->first();
                if ($checkExists && auth()->user()->id !== 1) {
                    session()->flash('error', 'Your transaction id is not valid / duplicate!');
                    return redirect($redirect);
                }
                $exchange->transaction_id = $request->transaction_id ?? null;
            }
        } else {
            $exchange = ExchangeSwap::find($request->id);
            $redirect = 'exchange-swap';
        }

        $exchange->approved_date = date('Y-m-d h:m:s');
        $exchange->status = $request->status ?? 0;
        $exchange->approver_comment = $request->approver_comment;
        $exchange->approved_by = auth()->user()->id;
        if ($exchange->save()) {
            if ($request->status) $this->sendEmail($request->id, $request->exchange_type);
            session()->flash('message', 'Your request was updated successfully!');
        }
        return redirect($redirect);
    }


    /**
     * @param ajax input request
     * @return HTML file for swap / deposit <JSON>
     **/
    public function getExchange(Request $request)
    {
        if ($request->id && $request->exchangeType) {
            $calculatedPrice = [];
            $exchange = ($request->exchangeType == '1') ? ExchangeDeposit::find($request->id) : ExchangeSwap::find($request->id);
            $template = ($exchange->exchange_type == '1' ? 'modal.deposit-view' : 'modal.swap-view');
            if ($request->exchangeType == '2') {
                $data = [
                    'swap_from' => $exchange->swap_from,
                    'swap_to' => $exchange->swap_to,
                    'swap_from_coin' => $exchange->swap_from_coin
                ];

                $calculatedPrice = $this->priceCalculation($data);
            }

            $returnHTML = view($template, [
                'exchange' => $exchange,
                'calculatedPrice' => $calculatedPrice
            ])->render();

            return response()->json(['success' => true, 'html' => $returnHTML]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * @param AJAX request 
     * @return currenct deposit address <JSON>
     **/
    public function depositAddress(Request $request) 
    {
        $address_url = ''; 
        $address = ''; 
        $depositMin = 0; 
        if ($request->coin) {
            $exchange = Coin::where('coin_id', $request->coin)->first();
            $depositMin = $exchange->deposit_min;
            if ($request->coin === 'indian_rupee') {
                $bank = Bank::where('user_id', 1)->where('status', 1)->orderBy('selected_account', 'ASC')->get(['name', 'bank_name', 'ifsc_code', 'account_number', 'upi', 'account_type']);
                return response()->json(['asset_type' => 'fiat', 'bank' => $bank, 'deposit_min' => $depositMin]);
            } else {
                $address_url = 'https://chart.googleapis.com/chart?chs=250x250&chld=M|0&cht=qr&chl=' . $exchange->address . '&choe=UTF-8';
                $address = $exchange->address;
            }
        }
        return response()->json(['asset_type' => 'crypto', 'address_url' => $address_url, 'address' => $address, 'deposit_min' => $depositMin]);
    }

    /**
     * @param Form input request
     * @return currency balance <JSON>
     **/
    public function currencyBalance(Request $request)
    {
        $availbleCoinBalance = 0;
        if ($request->coin) {
            $coin = Coin::where('coin_id', $request->coin)->first();
            $user_id = auth()->user()->id;
            $availbleCoinBalance = $this->swapModel->availbleCoinBalance($user_id, $request->coin);
            return response()->json(['status' => true, 'balance' => $availbleCoinBalance, 'min' => $coin->deposit_min, 'max' => $coin->deposit_max, 'symbol' => $coin->symbol]);
        }

        return response()->json(['status' => false]);
    }

    public function calculatePrice(Request $request)
    {
        $response = ['status' => false];
        if ($request->swap_from && $request->swap_to && $request->swap_from_coin) {
            $data = [
                'swap_from' => $request->swap_from,
                'swap_to' => $request->swap_to,
                'swap_from_coin' => $request->swap_from_coin
            ];

            $response = $this->priceCalculation($data);
        }

        return response()->json($response);
    }

    /**
     * @param $data <Array>
     * @return calculated price values
     **/
    protected function priceCalculation($data)
    {

        $swapFormInr = ($data['swap_from'] === 'indian_rupee' && $data['swap_to'] !== 'indian_rupee') ? true : false;
        $swapToInr =  ($data['swap_from'] !== 'indian_rupee' && $data['swap_to'] === 'indian_rupee') ? true : false;
        $swapFromPrice = ($data['swap_from'] === 'indian_rupee') ? 1 : $this->getPrice($data['swap_from'], $swapFormInr, $swapToInr);
        $swapToPrice = ($data['swap_to'] === 'indian_rupee') ? 1 : $this->getPrice($data['swap_to'], $swapFormInr, $swapToInr);
        $response['status'] = true;
        $response['swapFromPrice'] = $swapFromPrice;
        $response['swapToPrice'] = $swapToPrice;
        $toPrice = $swapFromPrice / $swapToPrice;
        $response['swapFees'] = $this->decimalConversion(($this->commision / 100) * $data['swap_from_coin']);
        $finalFromCoin = $data['swap_from_coin'] - $response['swapFees'];
        $response['finalFromCoin'] = $this->decimalConversion($finalFromCoin);
        $response['swapConvPrice'] = $this->decimalConversion($toPrice);
        $response['convertPrice'] = $this->decimalConversion(($swapFromPrice * $finalFromCoin) / $swapToPrice);

        return $response;
    }

    /**
     * @param input price
     * @return decimal Converted value
     **/
    protected function decimalConversion($toPrice, $decimal = 4)
    {
        $expo = pow(10, $decimal);
        return intval($toPrice * $expo) / $expo;
    }

    /**
     * @param $coin  
     * @return coingecko currency price
     **/
    private function getPrice($coin, $actionFromInr = false, $actionToInr = false)
    {
        $coinData = Coin::where('coin_id', $coin)->first();
		$coin = $coinData->coin;
        echo "<pre>"; print_r($coin); exit;
        $fetchPricingFromApi = false;
        if ($coinData) {
            $fetchPricingFromApi = $coinData->fetch_pricing_from_api;
            $this->coinToInrPercentage = $coinData->coin_to_inr_percentage;
            $this->coinToInrPercentageOpt = $coinData->coin_to_inr_percentage_option;
            $this->inrToCoinPercentage = $coinData->inr_to_coin_percentage;
            $this->inrToCoinPercentageOpt = $coinData->inr_to_coin_percentage_option;
            $this->coinToCoinPercentage = $coinData->coin_to_coin_percentage;
            $this->coinToCoinPercentageOpt = $coinData->coin_to_coin_percentage_option;
        }
        if ($fetchPricingFromApi != false) {
            if ($coin == 'tether-1') $coin = 'tether';
            $client = new \GuzzleHttp\Client();
            $endpoint = 'https://api.coingecko.com/api/v3/simple/price';
            $response = $client->request('GET', $endpoint, ['query' => [
                'ids' => $coin,
                'vs_currencies' => 'inr',
            ]]);
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $res = json_decode($response->getBody(), true);
                $inrPrice = $res[$coin]['inr'];
            } else {
                $inrPrice = 0;
            }
        } else {
            $inrPrice = $coinData->inr_price;
        }

        if (!$actionFromInr && $actionToInr) {
            if ($this->coinToInrPercentageOpt) {
                $inrPrice = $inrPrice + (($this->coinToInrPercentage / 100) * $inrPrice); // Coin to INR
            } else {
                $inrPrice = $inrPrice - (($this->coinToInrPercentage / 100) * $inrPrice); // Coin to INR
            }
        } else if ($actionFromInr && !$actionToInr) {
            if ($this->inrToCoinPercentageOpt) {
                $inrPrice = $inrPrice + (($this->inrToCoinPercentage / 100) * $inrPrice); // INR to Coin
            } else {
                $inrPrice = $inrPrice - (($this->inrToCoinPercentage / 100) * $inrPrice); // INR to Coin
            }
        } else if (!$actionFromInr && !$actionToInr) {
            if ($this->coinToCoinPercentageOpt) {
                $inrPrice = $inrPrice + (($this->coinToCoinPercentage / 100) * $inrPrice); // Coin to Coin
            } else {
                $inrPrice = $inrPrice - (($this->coinToCoinPercentage / 100) * $inrPrice); // Coin to Coin
            }
        }

        return $inrPrice;
    }

    public function getBalances()
    {
        $this->data['pageName'] = 'Exchange Balance';
        $this->data['coins'] = Coin::where('status', 1)->orderBy('sort_order', 'DESC')->get();
        return view('balance', ['data' => $this->data]);
    }

    public function sell(Request $request)
    {
        $role_id = auth()->user()->role_id;
        $data = Crypt::decrypt($request->segment(2));
        $exchangeSwap = new ExchangeSwap;
        $coinData = Coin::where('coin_id', $data['coin_id'])->first();
        echo "<pre>"; print_r($coinData); exit;
        if ($coinData) {
            $coinprice = $this->getPrice($coinData->coin_id);
            $balance = $exchangeSwap->availbleCoinBalance($data['user_id'], $data['coin_id']);
            $this->data['coinprice'] = $coinprice;
            $this->data['balance'] =  $balance;
            $this->data['coinData'] =  $coinData;
            $this->data['pageName'] = 'Sell Order';
            $this->data['bankDetails'] = Bank::where('user_id', $data['user_id'])->where('status', 0)->orderBy('is_primary', 'DESC')->get();
			
			if(count($this->data['bankDetails'])==0){
				session()->flash('error', 'Please update your bank details..');
				return redirect('balance');
			}
            return view('sell-form', $this->data);
        } else {
			session()->flash('error', 'Something went wrong!');
            return redirect('balance');
        }
    }
    public function sellOrder(Request $request)
    {
        $sellorder = SellOrder::firstOrNew(['id' => $request->input('hid')]);
        $sellorder->coin_id = $request->input('coin_id');
        $sellorder->deposit_currency = $request->input('deposit_currency');
        $sellorder->user_id = auth()->user()->id;
        $sellorder->coin_volume = $request->input('coin_volume');
        $sellorder->initial_price = $request->input('initial_price');
        $sellorder->remark = $request->input('remark');
        $sellorder->status = 1;
        if ($sellorder->save()) {
            if (is_array($request->input('banks'))) {
                foreach ($request->input('banks') as $banksval) {
                    $userbank = UserBank::firstOrNew(['order_id' => $sellorder->id, 'bank_id' => $banksval]);
                    $userbank->user_id = auth()->user()->id;
                    $userbank->order_id = $sellorder->id;
                    $userbank->bank_id = $banksval;
                    $userbank->save();
                }
            }
        }
        return redirect('/list-sellorder');
    }
    public function editSellorder(Request $request)
    {
        $role_id = auth()->user()->role_id; 
        $this->data['sellorder'] = SellOrder::where('id', $request->id)->first();
        $coinData = Coin::where('coin_id', $this->data['sellorder']->coin_id)->first();
        $exchangeSwap = new ExchangeSwap;
        $this->data['coin_id'] =  $this->data['sellorder']->coin_id;
        $this->data['coinprice'] =  $this->getPrice($this->data['sellorder']->coin_id);
        $this->data['balance'] =  $exchangeSwap->availbleCoinBalance($this->data['sellorder']->user_id, $this->data['sellorder']->coin_id);
        $this->data['coinData'] =  $coinData;
        $this->data['pageName'] = 'Edit Sell Order';
        $userbanks  = UserBank::select('bank_id')->where('user_id', $this->data['sellorder']->user_id)->where('order_id', $this->data['sellorder']->id)->get()->toArray();
        $this->data['userbanks'] = array_map('current', $userbanks);
        $this->data['bankDetails'] = Bank::where('user_id', $this->data['sellorder']->user_id)->where('status', 1)->orderBy('is_primary', 'DESC')->get();
        return view('sell-form', $this->data);
    }
    public function listSellOrder(Request $request)
    {
        $this->data['pageName'] = 'List sell order';
        $this->data['sellOrder'] = SellOrder::where('user_id', auth()->user()->id)->with('getcoin')->orderBy('id', 'DESC')->get();
        //echo "<pre>"; print_r($this->data['sellOrder']); exit;
        return view('listsell-order', ['data' => $this->data]);
    }
    public static function getCoinBalance($coin, $user_id = false) 
    {
        if ($user_id === false) {  
            $user_id = auth()->user()->id;
        }

        $exchangeSwap = new ExchangeSwap;
        $balance = $exchangeSwap->availbleCoinBalance($user_id, $coin);
        return $balance;
    }

    public function sendNotify()
    {
        $data = "Hello Sathish, New deposit posted by satzkk for the value of 500 BTC. Regards, Team Anyswap";
        $this->sendSMS($data);
    }

    public function getFees()
    {
        $this->data['pageName'] = 'Exchange Fees';
        $this->data['coins'] = Coin::where('status', 1)->orderBy('sort_order', 'ASC')->get();
        return view('exchange-fees', ['data' => $this->data]);
    }

    public function getCoins()
    {
        $this->data['pageName'] = 'Coin';
        $this->data['coins'] = Coin::orderBy('sort_order', 'ASC')->paginate(10);
        return view('coin.list', ['data' => $this->data]);
    }

    public function getCoin($id = 0)
    {
        $this->data['pageName'] = 'Coin';
        if ($id) {
            $coin = Coin::find($id);
        } else {
            $coin = [];
        }

        $this->data['coin'] = $coin;

        return view('coin.form', ['data' => $this->data]);
    }

    public function postCoin(Request $request)
    {
        $request->validate([
            'coin' => 'required',
            'symbol' => 'required',
            'coin_id' => 'required',
            'address' => 'required',
            'withdraw_min' => 'required',
            'withdraw_max' => 'required',
            'withdraw_fee' => 'required',
            'exchange_type' => 'required',
            'fetch_pricing_from_api' => 'required',
            'status' => 'required'
        ]);
        $id = $request->id ?? 0;
        $coin = Coin::findOrCreate($id);
        $coin->coin = $request->coin;
        $coin->symbol = $request->symbol;
        $coin->coin_id = $request->coin_id;
        $coin->address = $request->address;
        $coin->deposit_min = $request->withdraw_min;
        $coin->withdraw_min = $request->withdraw_min;
        $coin->withdraw_max = $request->withdraw_max;
        $coin->withdraw_fee = $request->withdraw_fee;
        $coin->exchange_type = $request->exchange_type;
        $coin->status = $request->status;
        $coin->fetch_pricing_from_api = $request->fetch_pricing_from_api;
        $coin->inr_price = $request->inr_price ?? 0;
        $coin->coin_to_inr_percentage = $request->coin_to_inr_percentage ?? 0;
        $coin->inr_to_coin_percentage = $request->inr_to_coin_percentage ?? 0;
        $coin->coin_to_coin_percentage = $request->coin_to_coin_percentage ?? 0;
        $coin->coin_to_inr_percentage_option = $request->coin_to_inr_percentage_option ?? 0;
        $coin->inr_to_coin_percentage_option = $request->inr_to_coin_percentage_option ?? 0;
        $coin->coin_to_coin_percentage_option = $request->coin_to_coin_percentage_option ?? 0;
        $coin->sort_order = $request->sort_order ?? 0;
        $coin->is_stacking = $request->is_stacking ?? 0;
        $coin->stacking_address = $request->stacking_address;
        if ($coin->save()) {
            session()->flash('message', 'Coin details are is updated successfully!');
        } else {
            session()->flash('error', 'Something went wrong!');
        }

        return redirect('coins');
    }

    public function getSettings()
    {
        $this->data['pageName'] = 'Coin Settings';
        $settings = Settings::where('settings', 'exchange_settings')->orderBy('config_sort', 'ASC')->get(['id', 'config_key', 'config_value']);

        $this->data['coinSettings'] = $settings;

        return view('coin.settings', ['data' => $this->data]);
    }

    public function postSettings(Request $request)
    {
        unset($request->_token);
        foreach ($request->all() as $key => $value) {
            Settings::where('config_key', $key)->update(['config_value' => $value]);
        }
        session()->flash('message', 'Coin settings details are is updated successfully!');
        return redirect('coins');
    }
    public function exchangeList()
    {
        $this->data['pageName'] = 'Exchange Listing';
        $this->data['coins'] = Coin::where('coin_id', '<>', 'indian_rupee')->orderBy('sort_order', 'ASC')->get();
        $this->data['sellorder_list'] = SellOrder::where('status', 1)->orderBy('id', 'DESC')->where('user_id', '!=', auth()->user()->id)->get();
        $this->data['userbanks'] = UserBank::with('getbankname')->groupBy('bank_id')->get();
        return view('exchange-list', ['data' => $this->data]); 
    }

    public function exchangeForm(Request $request)
    {

        $this->data['pageName'] = 'Buy Coin';

        $id = Crypt::decrypt($request->segment(3));

        $this->data['order_id']  = $id;

        $this->data['exchange_details'] = SellOrder::where('id', $id)->first();

        return view('exchange-form', ['data' => $this->data]);
    }

    public function coinRequest(Request $request, $id=null)
    {
        $this->data['pageName'] = 'Coin request';
		
        if ($request->isMethod('post')) {

            SellOrder::where('id', $request->input('sellorder_id'))->update(['status' => 2]);

            $sellorder = SellOrder::where('id', $request->input('sellorder_id'))->where('status', 2)->first();

            $order_request = new OrderRequest;

            $order_request->order_id = $request->input('sellorder_id');

            $order_request->from_user_id = auth()->user()->id;

            $order_request->to_user_id = $sellorder->user_id;

            $order_request->timer_start = date('Y-m-d H:i:s');

            $order_request->status = 1;

            $order_request->save();

            Session::put('payTimer', time());

            Session::put('order_request_id', $order_request->id);
			
			$user = auth()->user();
			
			$coinUserDetail = User::where('id',$sellorder->user_id)->first();
			
			if ($user->email!='') {
				$coinData = Coin::where('coin_id', $sellorder->coin_id)->first();
				$data = [
					'name' => $user->name,
					'email' => $coinUserDetail->email,
					'orderId' =>$order_request->id,
					'orderDetails' => $sellorder,
					'coinDetails' => $coinData,
					'requestDate' => date('Y-m-d H:i:s')
				];
				\Mail::send('emails.sell-order-request', $data, function ($message) use ($data) {
					$message->from(env('MAIL_FROM_ADDRESS'), 'AnySwap');
					$message->subject('【ANYSWAP】 Sell Order Request Details');
					$message->to(strtolower($data['email']));
				});
			}
            return view('coin-paying', ['data' => $this->data]);
        } elseif($id){
            $id = Crypt::decrypt($request->segment(2));
            $order_request = OrderRequest::where('id',$id)->count();
            if($order_request > 0){
                Session::put('payTimer', time());

                Session::put('order_request_id', $id);
            }else{
                session()->flash('message', 'Something went wrong....');
                return redirect()->back();
            }
        }
        if (time() - Session::get('payTimer') > 3000) {  

            $order_request = OrderRequest::where('id', Session::get('order_request_id'))->update(['status' => 0, 'timer_end' => date('Y-m-d H:i:s')]);

            return redirect()->back()->withInput();
        } else {

            return view('coin-paying', ['data' => $this->data]);
        }
    }
    public function uploadDocumentProof(Request $request)
    {

        $request->validate([
            'document_proof' => 'required',
        ]);

        $fileExtensions = array('jpeg', 'jpg', 'png', 'pdf');

        $document_proof = $request->file('document_proof');

        $transaction_proof =  $this->uploadFiles($document_proof, 'document_proof', $fileExtensions);

        $order_request = OrderRequest::where('id', Session::get('order_request_id'))->first();
		$order_request->transaction_proof = $transaction_proof;
		$order_request->status = 2;
		$order_request->timer_end = date('Y-m-d H:i:s');
		$order_request->save();
		if($order_request){
			$sellorder = SellOrder::where('id', $order_request->order_id)->first();
			$coinUserDetail = User::where('id',$sellorder->user_id)->first();
			if ($sellorder) {
				$coinData = Coin::where('coin_id', $sellorder->coin_id)->first();
				$data = [
					'name' => auth()->user()->name,
					'email' => $coinUserDetail->email,
					'orderId' =>$order_request->id,
					'coinDetails' => $coinData,
                    'transaction_proof' =>$transaction_proof,
					'requestDate' => date('Y-m-d H:i:s')
				];
				\Mail::send('emails.sell-order-request-document', $data, function ($message) use ($data) {
					$message->from(env('MAIL_FROM_ADDRESS'), 'AnySwap');
					$message->subject('【ANYSWAP】 Sell Order Document Upload Details');
                    $message->attach('C:\Users\admin\Documents\task/sample.pdf');
					$message->to(strtolower($data['email']));
				});
			}
		}
        return redirect('outcoming-coin-request');
    }
    public function outcomingCoinRequest(Request $request)
    {

        $this->data['pageName'] = 'My request status';

        $this->data['order_request'] = OrderRequest::with('getRequestedCoinDetails')->where('from_user_id', auth()->user()->id)->get();

        //echo "<pre>"; print_r($this->data['order_request']); exit;

        return view('outcoming_coin_request', ['data' => $this->data]);
    }
    public function incomingCoinRequest(Request $request)
    {

        $this->data['pageName'] = 'Incoming Coin Request';

        $this->data['incoming_coin_request'] = OrderRequest::with('getRequestedCoinDetails', 'getUserDetails')->where('to_user_id', auth()->user()->id)->get();


        return view('incoming_coin_request', ['data' => $this->data]);
    }
    public function updateCoinStatus(Request $request, $id = null)
    {
        if ($request->isMethod('post') && $request->input('coin_request_id')) {
            $order_request = OrderRequest::where('id', $request->input('coin_request_id'))->first();
            $order_request->status = $request->input('status');
            if ($order_request->save()) {
                $sellorder = SellOrder::where('id', $order_request->order_id)->first();
                if ($request->input('status') == 3) {
                    $exchange = new ExchangeDeposit();
                    $exchange->user_id = $order_request->from_user_id;
                    $exchange->exchange_type = 1;
                    $exchange->deposit_coin = $sellorder->coin_volume;
                    $exchange->deposit_currency = $sellorder->deposit_currency; 
                    $exchange->status = 1;
                    if ($exchange->save()) {
                        $sellorder->status = 3;
                        $sellorder->save();
                    } 
                } else {
                    $sellorder->status = 1;
                    $sellorder->save();
                }
                return redirect('incoming-coin-request');
            }
        } else {
            $this->data['pageName'] = 'Update Status Incoming Coin Request';
            $requestedId = Crypt::decrypt($request->segment(2));
            $this->data['incoming_coin_request'] = OrderRequest::with('getRequestedCoinDetailsOne', 'getUserDetails')->where('id', $requestedId)->get();
            $this->data['incoming_request_id'] = $requestedId;
            return view('update-coin-status', $this->data);
        }
    }
    public function advertiserDetail(Request $request, $id = null){
        $this->data['pageName'] = 'Incoming Coin Request';
        $this->data['userInfo'] = User::find($id);
        return view('advertiser-detail', ['data' => $this->data]);
    }
	
	public function sellOrderFillter(Request $request){
		
		
		if($request->input('banks')){
			
			$bankIds = UserBank::where('bank_id',$request->input('banks'))->pluck('bank_id')->groupBy('bank_id')->toArray();
			
			$orderIds = UserBank::whereIn('bank_id',$bankIds)->pluck('order_id')->toArray();
		}
		
		echo "<pre>"; print_r($this->data['userbanks']); exit;
		
	}
}
