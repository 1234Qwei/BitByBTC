<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TradePairs;
use App\Models\Coin;
use App\Models\OrderTemp;
use App\Models\CoinOrder;
use Helpers;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    protected $userId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                $user = Auth::user();
                $this->userId = $user->id;
            }
            return $next($request);
        });
    }

    function advanceTrade($pairSymbol)
    {
        $splitPair = explode('_', $pairSymbol);
        $firstCurr = strtoupper(strip_tags(trim($splitPair[0])));
        $secondCurr = strtoupper(strip_tags(trim($splitPair[1])));
        $curs =  $firstCurr . $secondCurr;
        $str = file_get_contents('https://api.binance.com/api/v3/ticker/24hr?symbol=' . $curs);
        $json = array_values(json_decode($str, true));
        $last_price = $json[5];
        $pairid = TradePairs::where(['from_symbol' => $firstCurr, 'to_symbol' => $secondCurr])->select('id')->first()->id;
        $up = TradePairs::where('id', $pairid)->update(array('last_price' => $last_price));

        if (Auth::check()) {
            $user = auth()->user();
        } else {
            $user = '';
        }

        $pairs = TradePairs::getFullPairs();
        $page = 2;

        return view('advance_trade.trade-advanced', compact('pairSymbol', 'user', 'pairs', 'page'));
    }

    function showAdvanceUserBalance()
    {
        $id = $this->userId;
        $response = '';
        $userbalance = $curr = array();

        $allcurr = Coin::where('status', 1)->select('image', 'symbol', 'id', 'name', 'min_withdraw', 'max_withdraw', 'with_fee', 'withdarw_status', 'withdarw_content', 'withdraw_maintenance')->get()->map(function ($curr) {
            return ['key' => $curr->symbol, 'value' => $curr];
        })->pluck('value', 'key')->toArray();

        // $userbalance = Wallet::getBalance($id);
        foreach ($allcurr as $curr) {
            $symbol = $curr['symbol'];
            $src = $curr['image'];
            $inorders = Helpers::inOrders($symbol, $id);
            $inorders = $inorders['inorder_buy'] + $inorders['inorder_sell'] + $inorders['inorder_crypto_withdraw'] + $inorders['inorder_fiat_withdraw'];
            $inorders = rtrim(rtrim(sprintf('%.8F', $inorders), '0'), ".");
            // if (isset($userbalance[$curr['id']])) {
            // $balance = rtrim(rtrim(sprintf('%.8F', $userbalance[$curr['id']]), '0'), ".");
            // } else {
            $balance = 0;
            // }
            $total = $inorders + $balance;

            if ($id) {
                $response .= '<tr><td class=""><img class="portlet-table-cc-icon mr-2" src="' . $src . '"> ' . $symbol . '</td><td id="' . $symbol . '_bal">' . $balance . '</td><td class="' . $symbol . '"balance">' . $total . '</td><td class="text-center ' . $symbol . '"balance">' . $inorders . '</td></tr>';
            } else {
                $response .= '<tr><td class=""><img class="portlet-table-cc-icon mr-2" src="' . $src . '"> ' . $symbol . '</td><td id="' . $symbol . '_bal">-</td><td class="' . $symbol . '"balance">-</td><td class="text-center ' . $symbol . '"balance">-</td></tr>';
            }
        }

        return response($response);
    }

    public function chart($coin, $type)
    {
        if ($type == "symbols") {
            return response('{"name":"' . $coin . '","exchange-traded":"Bit2atm","exchange-listed":"Bit2atm","timezone":"America/New_York","minmov":1,"minmov2":0,"pointvalue":1,"session":"24x7","has_intraday":true,"has_no_volume":false,"description":"' . $coin . '","type":"stock","supported_resolutions":["1","5","15","30","60","D","2D","3D","W","3W","M","6M"],"pricescale":100000000,"volume_precision":8,"ticker":"' . $coin . '"}');
        } else  if ($type == "config") {
            return response('{"supports_search":true,"supports_group_request":false,"supports_marks":true,"supports_timescale_marks":true,"supports_time":true,"exchanges":[{"value":"","name":"All Exchanges","desc":""},{"value":"Bit2atm","name":"Bit2atm","desc":"Bit2atm"}],"symbols_types":[{"name":"All types","value":""},{"name":"Stock","value":"stock"},{"name":"Index","value":"index"}],"supported_resolutions":["1","5","15","30","60","D","2D","3D","W","3W","M","6M"]}');
        } else if ($type == "history") {
            $from = $_GET['from'];
            $to = $_GET['to'];
            $exp = explode('_', $coin);
            $firstCurr = strtoupper($exp[0]);
            $secondCurr = strtoupper($exp[1]);
            $pairId = TradePairs::where(['to_symbol' => $secondCurr, 'from_symbol' => $firstCurr, 'status' => 1])->select('id')->first()->id;
            return $this->chartData($pairId, $from, $to);
        }
    }

    private function chartData($pair, $from, $to)
    {
        $tradepair = TradePairs::where('id', $pair)->select('from_symbol', 'to_symbol')->first();
        $first = $tradepair->from_symbol;
        $secont = $tradepair->to_symbol;

        $tradepair = $first . '_' . $secont;

        $tradeair = $first . $secont;
        $str = file_get_contents('https://api.binance.com/api/v3/klines?symbol=' . $tradeair . '&interval=5m');
        $query = array_values(json_decode($str, true));

        if (!$query) {
            $out = array('s' => 'no_data');
            return json_encode($out, JSON_PRETTY_PRINT);
        }
        $i = 0;
        foreach ($query as $que) {
            $o[] = $que[1];
            $c[] = $que[4];
            $l[] = $que[3];
            $h[] = $que[2];
            $v[] = $que[5];
            $t[] = $que[0];
            $i++;
        }
        $out = array('t' => $t, 'o' => $o, 'h' => $h, 'l' => $l, 'c' => $c, 'v' => $v, 's' => 'ok');

        return json_encode($out, JSON_PRETTY_PRINT);
    }

    public function getPairDataAdvance($pair, $decvalue)
    {
        $response = [];
        $splitPair = explode('_', $pair);
        $firstCurr = strtoupper(strip_tags(trim($splitPair[0])));
        $secondCurr = strtoupper(strip_tags(trim($splitPair[1])));
        $pairDetails = TradePairs::where('from_symbol', $firstCurr)->where('to_symbol', $secondCurr)->select('id', 'min_price', 'max_price', 'trade_fee', 'taker_trade_fee', 'last_price', 'min_amt', 'from_symbol_id', 'to_symbol_id')->first();
        if ($pairDetails) {
            $pairId = $pairDetails->id;
            $result['pair_id'] = $pairId;
            $result['from_cur'] = $firstCurr;
            $result['to_cur'] = $secondCurr;
            $result['from_bal'] = 0;
            $result['to_bal'] = 0;
            $result['pair'] = $secondCurr . '/' . $firstCurr;
            $result['my_orders'] = '0';
            $result['min_amt'] = $pairDetails->min_amt;
            $result['min_price'] = $pairDetails->min_price;
            $result['max_price'] = $pairDetails->max_price;
            $result['trade_fee'] = $pairDetails->trade_fee;
            $result['taker_trade_fee'] = $pairDetails->taker_trade_fee;
            $result['last_price'] = $pairDetails->last_price;
            $inr_value = Coin::where('symbol', $firstCurr)->select('inr_value')->first()->inr_value;
            $result['usd_val'] = $inr_value;
            $result['buy_orders'] = self::getadvanceBuySellOrders($pairId, 'buy', '', $decvalue);
            $result['limit_buy_orders'] = self::getadvanceBuySellOrders($pairId, 'buy', 6, $decvalue);
            $result['sell_orders'] = self::getadvanceBuySellOrders($pairId, 'sell', '', $decvalue);
            $result['limit_sell_orders'] = self::getadvanceBuySellOrders($pairId, 'sell', 6, $decvalue);
            $result['sell_orderss'] = self::getadvanceBuySellOrders($pairId, 'sell', '', $decvalue);
            $result['limit_sell_orderss'] = self::getadvanceBuySellOrders($pairId, 'sell', 6, $decvalue);
            $result['market_orders'] = self::getallFilledOrders($pairId);
            $result['open_orders'] = '0';
            $result['stop_orders'] = '0';
            // $userId = session('tmaitb_user_id');
            // if ($userId != "") {
            //     $getBalance1 = Wallet::getBalance($userId, $pairDetails->from_symbol_id);
            //     $getBalance2 = Wallet::getBalance($userId, $pairDetails->to_symbol_id);
            //     $result['from_bal'] = $getBalance1;
            //     $result['to_bal'] = $getBalance2;
            //     $result['my_orders'] = self::getMyTradeHistory($pairId, $userId);

            //     $result['open_orders'] = self::getActiveOrders($pairId, $userId);
            //     $result['stop_orders'] = self::getStopOrders($pairId, $userId);
            // }
            $result['trade_data'] = Helpers::getTradeData($pairId, $firstCurr, $secondCurr);
            $result['price_range'] = Helpers::priceRange($pairId);
            $response = array('status' => 'success', 'result' => $result);
        }

        return response()->json($response);
    }

    public static function getallFilledOrders($pairId)
    {
        $response = array();
        $result = array();
        $pairs = request()->segment(2);
        $orders = OrderTemp::where('pair', $pairId)->where('cancel_id', NULL)->select('askPrice', 'filledAmount', 'updated_at', 'sellerUserId', 'buyerUserId', 'sellorderId', 'buyorderId')->orderBy('id', 'desc')->limit(40)->get();
        if (!$orders->isEmpty()) {
            foreach ($orders as $order) {
                $sellorderId = $order->sellorderId;
                $buyorderId = $order->buyorderId;
                $updated_at = $order->updated_at;
                $filledAmount = rtrim(rtrim(sprintf('%.8F', $order->filledAmount), '0'), ".");
                $activePrice = rtrim(rtrim(sprintf('%.8F', $order->askPrice), '0'), ".");
                if ($filledAmount > 0) {
                    $total = $activePrice * $filledAmount;
                    $total = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
                    $result['datetime'] = date('H:i:s', strtotime($updated_at));
                    $result['price'] = $activePrice;
                    $result['amount'] = $filledAmount;
                    $result['sellorderId'] = $sellorderId;
                    $result['buyorderId'] = $buyorderId;
                    $response[] = $result;
                }
            }

            $splitPair = explode('_', $pairs);
            $firstCurr = strtoupper(strip_tags(trim($splitPair[0])));
            $secondCurr = strtoupper(strip_tags(trim($splitPair[1])));
            $curs = $firstCurr . $secondCurr;
            $str = file_get_contents('https://api.binance.com/api/v3/trades?symbol=' . $curs . '');
            $bids = array_values(json_decode($str, true));
            $response = array();
            if (is_array($bids) || is_object($bids)) {
                foreach ($bids as $bit) {
                    $bb = array();
                    $bb['amount'] = $bit['qty'];
                    $bb['price'] = $bit['price'];
                    $bb['datetime'] = date('H:i:s', $bit['time']);
                    $bb['isBuyerMaker'] = $bit['isBuyerMaker'];

                    $response[] = $bb;
                }
            }
        } else {
            $splitPair = explode('_', $pairs);
            $firstCurr = strtoupper(strip_tags(trim($splitPair[0])));
            $secondCurr = strtoupper(strip_tags(trim($splitPair[1])));
            $curs = $firstCurr . $secondCurr;
            $str = file_get_contents('https://api.binance.com/api/v3/trades?symbol=' . $curs . '');
            $bids = array_values(json_decode($str, true));
            $response = array();
            if (is_array($bids) || is_object($bids)) {
                foreach ($bids as $bit) {
                    $bb = array();

                    $bb['amount'] = $bit['qty'];
                    $bb['price'] = $bit['price'];
                    $bb['datetime'] = date('H:i:s', $bit['time']);
                    $bb['isBuyerMaker'] = $bit['isBuyerMaker'];

                    $response[] = $bb;
                }
            }
        }

        return $response;
    }

    public static function getadvanceBuySellOrders($pair, $type, $limit = '', $decval)
    {
        $pairs = request()->segment(2);
        if ($type == 'sell') {
            if ($limit != '') {
                $openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'desc')->limit($limit)->get();
            } else {
                $openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'asc')->get();
            }
        } else {
            if ($limit != '') {
                $openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'desc')->limit($limit)->get();
            } else {
                $openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'desc')->get();
            }
        }
        $response = $responses = array();
        if (!$openOrders->isEmpty()) {
            foreach ($openOrders as $order) {
                $price1 = $order->Price;
                $price = number_format($price1, $decval, '.', '');
                $amount = $order->amount;
                // $filledAmount = TradeModel::checkOrdertemp($orderId, $tempId);
                $filledAmount = $amount; //($filledAmount) ? $amount - $filledAmount : $amount;
                if (isset($responses[$price])) {
                    $old_amount = $responses[$price]['amount'];
                    $old_amount += $filledAmount;
                    $total = $old_amount * $price;
                    $responses[$price]['amount'] = $old_amount;
                    $responses[$price]['total'] = $total;
                } else {
                    $pairs = request()->segment(2);
                    $splitPair = explode('_', $pairs);
                    $firstCurr = strtoupper(strip_tags(trim($splitPair[0])));
                    $secondCurr = strtoupper(strip_tags(trim($splitPair[1])));
                    $curs = $firstCurr . $secondCurr;
                    $a = Helpers::depth($curs);
                    if ($type == "buy") {
                        $bids = $a['bids'];
                    } else {
                        $bids = $a['asks'];
                    }
                    $response = array();
                    if (is_array($bids) || is_object($bids)) {
                        foreach ($bids as $bit) {
                            $bb = array();
                            $bb['amount'] = $bit[1];
                            $bb['price'] = $bit[0];
                            $bb['total'] = $bit[0] * $bit[1];
                            $bb['cls'] = '';
                            $response[] = $bb;
                        }
                    }
                }
            }
            foreach ($responses as $key => $value) {
                $response[] = $value;
            }
        } else {
            $splitPair = explode('_', $pairs);
            $firstCurr = strtoupper(strip_tags(trim($splitPair[0])));
            $secondCurr = strtoupper(strip_tags(trim($splitPair[1])));
            $curs = $firstCurr . $secondCurr;
            $a = Helpers::depth($curs);
            if ($type == "buy") {
                $bids = $a['bids'];
            } else {
                $bids = $a['asks'];
            }

            $response = array();

            if (is_array($bids) || is_object($bids)) {
                foreach ($bids as $bit) {
                    $bb = array();
                    $bb['amount'] = $bit[1];
                    $bb['price'] = $bit[0];
                    $bb['total'] = $bit[0] * $bit[1];
                    $bb['cls'] = '';
                    $response[] = $bb;
                }
            }
        }

        return $response;
    }


    public function coinPairs($type = '', $pairid = '')
    {
        $coinPairs = array();
        $fav = $favValues = $btcValues = $ethValues = $usdtValues = $bnbValues = $ownValues = "";

        $pairDetails = \DB::select("select b.id,b.last_price, b.from_symbol, b.to_symbol,a.askPrice as yesterday_price,min(askPrice) as low_price,max(askPrice) as high_price, (sum(askPrice * filledAmount)) as volume FROM tmp_order a right join trade_pair b on a.pair = b.id and a.created_at >= date_add(now(), interval -1 day) and a.cancel_id is null where b.status = 1 GROUP BY b.id, b.from_symbol ");

        // $id = $this->userId;
        // if ($id) {
        //     $get_fav = User::where('id', $id)->select('fav_pairs')->first();
        //     $get_fav = $get_fav->fav_pairs;
        //     $fav = explode(',', $get_fav);
        // }
        foreach ($pairDetails as $pairs) {
            $all_active_pairs = array();
            $pairdetails = '';
            $fromSymbol = $pairs->from_symbol;
            $toSymbol = $pairs->to_symbol;
            $forUrl = $toSymbol . '_' . $fromSymbol;
            $forName = $toSymbol . '/' . $fromSymbol;

            $lastId = "id=last_price_" . $forUrl;
            $changeId = "id=change_" . $forUrl;
            $volumeId = "id=volume_" . $forUrl;
            $activeId = "id=active_pair_" . $forUrl;
            $activeCls = "all_active_pairs active_pair_" . $forUrl;
            $activeCls = "class='" . $activeCls . "'";

            $lastPrice = number_format($pairs->last_price, 8, '.', ',');


            $yesterPrice = $pairs->yesterday_price == '' ? 0 : $pairs->yesterday_price;
            $high_price = $pairs->high_price == '' ? 0 : $pairs->high_price;
            $low_price = $pairs->low_price == '' ? 0 : $pairs->low_price;

            $fiat = "INR";
            $convertionnew = Helpers::getconvertionprice($fromSymbol, $toSymbol);
            $convertionPrice = $convertionnew == '' ? 0 : rtrim(rtrim(sprintf('%.4F', $convertionnew), '0'), ".");
            if ($fromSymbol != "INR") {
                $convertprice = $convertionPrice;
            } else {
                $convertprice = $convertionPrice;
            }
            $convert_price = number_format($convertprice, 2, '.', ',');

            $convertvalue = $lastPrice . ' / ' . $convert_price . " " . $fiat;
            $clsName = "class=text-success";
            if ($yesterPrice <= 0) {
                $changePer = 0;
                $arrow = "";
            } else {
                $changePrice = ($lastPrice - $yesterPrice) / $yesterPrice;
                $changePer = $changePrice * 100;
                if (($lastPrice >= $yesterPrice)) {
                    $clsName = "class=text-success";
                    $arrow = "+";
                } else {
                    $clsName = "class=text-danger";
                    $arrow = "";
                }
            }
            $decimal = 8;
            $changePer = $arrow . number_format($changePer, 2, '.', ',') . '%';
            //$volume = ($pairs->volume == null) ? "0.00" : rtrim(rtrim(sprintf('%.8F', $pairs->volume), '0'), ".");

            $volume = ($pairs->volume == null) ? "0.00" : number_format($pairs->volume, 2, '.', ',');
            $favClass = 0;

            $fav_id = $pairs->id;
            if ($pairid == $pairs->id) {
                $style = "background-color: #F2F2FC;";
            } else {
                $style = "";
            }
            $url = \URL::to('/') . "/assets/images/copy_img.png";
            $url1 = \URL::to('/') . "/assets/images/copy_ho.png";
            $url3 = Helpers::getCurrencyImage($toSymbol);
            $tradeurl = \URL::to('/trade') . "/" . $forUrl;
            switch ($toSymbol) {
                case 'BTC':
                    if ($fav) {
                        if (in_array($pairs->id, $fav)) {
                            if ($type == '1') {
                                $favValues .= '<tr class="fav' . $fav_id . '"><td class="portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
                            } else if ($type == '2') {
                                $favValues .= '<tr class="fav' . $fav_id . '" style="' . $style . '"><td class="portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><img height="20" width="20" src="' . $url3 . '"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">' . $convertionnew . '</span> <span class ="light">' . $fiat . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td>' . $low_price . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . " " . $fromSymbol . '</td><td class="text-center td_copy" onclick="tradePairChange(\'' . $forUrl . '\')"><img title="Open in a new window" class="copy_img" src="' . $url . '"><img title="Open in a new window" class="copy_ho" src="' . $url1 . '"></td></tr>';
                            } else {
                                $favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
                            }
                            $favClass = 1;
                        }
                    }
                    if ($favClass) {
                        $fav_txt = '<i class="fa fa-fw fa-star"></i>';
                    } else {
                        $fav_txt = '<i class="fa fa-fw fa-star-o"></i>';
                    }

                    if ($type == '1') {

                        $btcValues .= '<tr style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td  class="text-right"onclick="tradePairChange(\'' . $forUrl . '\')">' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
                    } elseif ($type == '2') {

                        $btcValues .= '<tr style="cursor:pointer; ' . $style . '  " ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><img height="20" width="20" src="' . $url3 . '"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">' . $convertionnew . '</span> <span class="light">' . $fiat . '</span></td><td  class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $low_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . " " . $fromSymbol . '</td><td class="text-center td_copy"><a href = "' . $tradeurl . '" target="_blank"><img title="Open in a new window" class="copy_img" src="' . $url . '" ><img title="Open in a new window" class="copy_ho" src="' . $url1 . '"></a></td></tr>';
                    } else {


                        $btcValues .= '<tr style="cursor:pointer;"><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')" >' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
                    }
                    break;
                case 'BNB':
                    if ($fav) {
                        if (in_array($pairs->id, $fav)) {
                            if ($type == '1') {
                                $favValues .= '<tr class="fav' . $fav_id . '"><td class="text-right" onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
                            } elseif ($type == '2') {
                                $favValues .= '<tr class="fav' . $fav_id . '" style="' . $style . '"><td class="text-right" onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><img height="20" width="20" src="' . $url3 . '"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">' . $convertionnew . '</span> <span class ="light">' . $fiat . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td>' . $low_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . " " . $fromSymbol . '</td></tr>';
                            } else {
                                $favValues .= '<tr class="fav' . $fav_id . '"><td class="text-right" onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
                            }
                            $favClass = 1;
                        }
                    }
                    if ($favClass) {
                        $fav_txt = '<i class="fa fa-fw fa-star"></i>';
                    } else {
                        $fav_txt = '<i class="fa fa-fw fa-star-o"></i>';
                    }

                    if ($type == '1') {
                        $bnbValues .= '<tr style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
                    } elseif ($type == '2') {
                        $bnbValues .= '<tr style="cursor:pointer; ' . $style . '" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><img height="20" width="20" src="' . $url3 . '"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">' . $convertionnew . '</span> <span class="light">' . $fiat . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $low_price . '</td><td class="text-right">' . $volume . " " . $fromSymbol . '</td></tr>';
                    } else {
                        $bnbValues .= '<tr style="cursor:pointer;"><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
                    }
                    break;
                case 'OWN':
                    if ($fav) {
                        if (in_array($pairs->id, $fav)) {
                            if ($type == '1') {
                                $favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
                            } elseif ($type == '2') {
                                $favValues .= '<tr class="fav' . $fav_id . '" style="' . $style . '"><td class="text-right" onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><img height="20" width="20" src="' . $url3 . '"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">' . $convertionnew . '</span> <span class ="light">' . $fiat . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td>' . $low_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . " " . $fromSymbol . '</td></tr>';
                            } else {
                                $favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
                            }
                            $favClass = 1;
                        }
                    }
                    if ($favClass) {
                        $fav_txt = '<i class="fa fa-fw fa-star"></i>';
                    } else {
                        $fav_txt = '<i class="fa fa-fw fa-star-o"></i>';
                    }

                    if ($type == '1') {
                        $ownValues .= '<tr style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
                    } elseif ($type == '2') {
                        $ownValues .= '<tr style="cursor:pointer; ' . $style . '" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><img height="20" width="20" src="' . $url3 . '"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">' . $convertionnew . '</span> <span class="light">' . $fiat . '</span></td><td class="text-right"  onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $low_price . '</td><td class="text-right">' . $volume . " " . $fromSymbol . '</td></tr>';
                    } else {
                        $ownValues .= '<tr style="cursor:pointer;"><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right"  onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
                    }
                    break;
                case 'USDT':
                    if ($fav) {
                        if (in_array($pairs->id, $fav)) {
                            if ($type == '1') {
                                $favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
                            } elseif ($type == '2') {
                                $favValues .= '<tr class="fav' . $fav_id . '" style="' . $style . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><img height="20" width="20" src="' . $url3 . '"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">' . $convertionnew . '</span> <span class ="light">' . $fiat . '</span></td><td class="text-right"  onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right"  onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td>' . $low_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . " " . $fromSymbol . '</td></tr>';
                            } else {
                                $favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
                            }
                            $favClass = 1;
                        }
                    }
                    if ($favClass) {
                        $fav_txt = '<i class="fa fa-fw fa-star"></i>';
                    } else {
                        $fav_txt = '<i class="fa fa-fw fa-star-o"></i>';
                    }

                    if ($type == '1') {
                        $usdtValues .= '<tr style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
                    } elseif ($type == '2') {
                        $usdtValues .= '<tr style="cursor:pointer; ' . $style . '" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><img height="20" width="20" src="' . $url3 . '"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">' . $convertionnew . '</span> <span class="light">' . $fiat . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $low_price . '</td><td>' . $volume . " " . $fromSymbol . '</td></tr>';
                    } else {
                        $usdtValues .= '<tr style="cursor:pointer;"><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
                    }
                    break;
                case 'ETH':
                    if ($fav) {
                        if (in_array($pairs->id, $fav)) {
                            if ($type == '1') {
                                $favValues .= '<tr class="fav' . $fav_id . '"><td class="portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')"><i class="fa fa-fw fa-star"></i></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
                            } elseif ($type == '2') {
                                $favValues .= '<tr class="fav' . $fav_id . '" style="' . $style . '"><td  class="portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')"><i class="fa fa-fw fa-star"></i></td><td class="text-right"  onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><img height="20" width="20" src="' . $url3 . '"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">' . $convert_price . '</span> <span class ="light">' . $fiat . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td>' . $low_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . " " . $fromSymbol . '</td></tr>';
                            } else {

                                $favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right"  onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
                            }
                            $favClass = 1;
                        }
                    }
                    if ($favClass) {
                        $fav_txt = '<i class="fa fa-fw fa-star"></i>';
                    } else {
                        $fav_txt = '<i class="fa fa-fw fa-star-o"></i>';
                    }

                    if ($type == '1') {

                        $ethValues .= '<tr style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td class="text-right"  onclick="tradePairChange(\'' . $forUrl . '\')">' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td  class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
                    } elseif ($type == '2') {

                        $ethValues .= '<tr style="cursor:pointer;' . $style . '" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><img height="20" width="20" src="' . $url3 . '"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">' . $convert_price . '</span> <span class="light">' . $fiat . '</span></td><td class="text-right"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $low_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . " " . $fromSymbol . '</td></tr>';
                    } else {
                        $ethValues .= '<tr style="cursor:pointer;"><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')" >' . $fav_txt . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
                    }
                    break;
            }
        }
        $No_record_fou = trans('app_lang.no_data_found');
        if ($btcValues == '') {
            $btcValues = '<div style="text-align:center;"><span>' . $No_record_fou . '</span></div>';
        }

        if ($ethValues == '') {
            $ethValues = '<div style="text-align:center;"><span>' . $No_record_fou . '</span></div>';
        }

        if ($usdtValues == '') {
            $usdtValues = '<div style="text-align:center;"><span>' . $No_record_fou . '</span></div>';
        }


        if ($bnbValues == '') {
            $bnbValues = '<div style="text-align:center;"><span>' . $No_record_fou . '</span></div>';
        }

        if ($ownValues == '') {
            $ownValues = '<div style="text-align:center;"><span>' . $No_record_fou . '</span></div>';
        }

        if ($favValues == '') {
            $favValues = '<div style="text-align:center;" class="tet no-fav"><span>' . $No_record_fou . '</span></div>';
        }

        $coinPairs['BTC'] = $btcValues;
        $coinPairs['ETH'] = $ethValues;
        $coinPairs['USDT'] = $usdtValues;
        $coinPairs['OWN'] = $ownValues;
        $coinPairs['BNB'] = $bnbValues;
        $coinPairs['Fav'] = $favValues;

        return response()->json($coinPairs);
    }
}
