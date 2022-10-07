<?php

use App\Models\Coin;
use App\Models\TradePairs;
use App\Models\CoinOrder;
use App\Models\OrderTemp;

class Helpers
{
    public static function hello()
    {
        return 'Hi! Satz!';
    }

    public static function inOrders($curr, $id)
    {
        // $inorder['inorder_buy'] = \DB::table('redor_nioc')->where('user_id', $id)->where('Type', 'buy')->where('firstCurrency', $curr)->whereIn('status', ['active', 'partially', 'stoporder'])->sum('Total');
        $inorder['inorder_buy'] = 0;

        // $inorder['inorder_sell'] = \DB::table('redor_nioc')->where('user_id', $id)->where('Type', 'sell')->where('secondCurrency', $curr)->whereIn('status', ['active', 'partially', 'stoporder'])->sum('Amount');
        $inorder['inorder_sell'] = 0;

        // $inorder['exchange_sell'] = \DB::table('egnahcxe')->where('user_id', $id)->where('type', 'sell')->where('from_symbol', $curr)->whereIn('status', ['pending'])->sum('Amount');
        $inorder['exchange_sell'] = 0;

        // $inorder['exchange_buy'] = \DB::table('egnahcxe')->where('user_id', $id)->where('type', 'buy')->where('from_symbol', $curr)->whereIn('status', ['pending'])->sum('total');
        $inorder['exchange_buy'] = 0;

        // $inorder['inorder_crypto_withdraw'] = \DB::table('wardhtiw')->where('user_id', $id)->where('currency', $curr)->where('status', 'Pending')->sum('amount');
        $inorder['inorder_crypto_withdraw'] = 0;

        // $cur_id = self::getCurrencyid($curr);
        // $inorder['inorder_fiat_withdraw'] = \DB::table('wardhtiw_taif')->where(['user_id' => $id, 'currency_id' => $cur_id])->whereIn('status', ['Pending', 'Processing'])->sum('amount');
        $inorder['inorder_fiat_withdraw'] = 0;

        return $inorder;
    }

    public static function getCurrencyid($symbol)
    {
        return Coin::where('symbol', $symbol)->select('id')->first()->id;
    }

    public static function getTradeData($pair_id, $firstCurrency, $secondCurrency)
    {

        $x = array('volume' => '0.0000', 'change' => '0.0000', 'high' => '0.0000', 'low' => '0.0000', 'class' => "posVal");
        $price = OrderTemp::where('pair', $pair_id)->where('cancel_id', NULL)->orderBy('id', 'desc');
        if ($price->count() > 0) {
            $price = $price->first();
            $today_open = $price->askPrice;
            $yesterday = date('Y-m-d H:i:s', strtotime("-1 days"));
            $change_price = OrderTemp::where('datetime', '>=', $yesterday)->where('cancel_id', NULL)->where('pair', $pair_id)->select(\DB::raw('SUM(askPrice * filledAmount) as total_volume'), \DB::raw('askPrice as price'))->orderBy("id", "asc");
            $highprice = OrderTemp::where('pair', $pair_id)->where('cancel_id', NULL)->select(\DB::raw('askPrice as price'))->where('datetime', '>=', $yesterday)->orderBy("askPrice", "desc");
            $lowprice = OrderTemp::where('pair', $pair_id)->where('cancel_id', NULL)->select(\DB::raw('askPrice as price'))->where('datetime', '>=', $yesterday)->orderBy("askPrice", "asc");
            if ($change_price->count() > 0) {
                $bitcoin_rate = $change_price->first()->price;
                $daily_change = $today_open - $bitcoin_rate;
                $arrow = ($today_open > $bitcoin_rate) ? "+" : "";
                $class = ($today_open >= $bitcoin_rate) ? "posVal" : "negVal";
                $per = ($daily_change / $bitcoin_rate) * 100;
                $per = $arrow . number_format((float) $per, 2, '.', '');
                $daily = $arrow . number_format((float) $daily_change, 2, '.', '');
                $x['change'] = $per;
                $x['daily'] = $daily;
                $x['class'] = $class;
                $vol_val = $change_price->first()->total_volume;
                $x['volume'] = number_format((float) $vol_val, 4, '.', '');
            } else {
                $x['daily'] = '0.00';
                $x['change'] = '0.00';
            }
            if ($highprice->count() > 0) {
                $x['high'] = number_format((float) $highprice->first()->price, 8, '.', '');
            }
            if ($lowprice->count() > 0) {
                $x['low'] = number_format((float) $lowprice->first()->price, 8, '.', '');
            }
        } else {
            $curs = $firstCurrency . $secondCurrency;
            $str = file_get_contents('https://api.binance.com/api/v3/ticker/24hr?symbol=' . $curs);
            $json = array_values(json_decode($str, true));
            $today_open = $json[5];
            $bitcoin_rate = $json[4];
            $daily_change = $today_open - $bitcoin_rate;
            $arrow = ($today_open > $bitcoin_rate) ? "+" : "";
            $class = ($today_open >= $bitcoin_rate) ? "posVal" : "negVal";
            $per = ($daily_change / $bitcoin_rate) * 100;
            $per = $arrow . number_format((float) $per, 2, '.', '');
            $daily = $arrow . number_format((float) $daily_change, 2, '.', '');

            $x['high'] = $json[12];
            $x['volume'] = $json[14];
            $x['low'] = $json[13];
            $x['change'] = $per;
            $x['daily'] = $daily;
            $x['class'] = $class;;
            $x['lastprice'] = '0.00';
        }
        $x['europrice'] = self::getconvertionprice($firstCurrency, $secondCurrency);
        $x['lastprice'] = self::lastmarketprice($pair_id);

        return $x;
    }

    public static function getconvertionprice($fromSymbol, $toSymbol)
    {
        $getpairs = TradePairs::where('from_symbol', $fromSymbol)->first();
        return number_format($getpairs->convertedeur, 6);
    }

    protected static function lastmarketprice($id)
    {
        $result = CoinOrder::where('pair', $id)->where('status', 'filled')->select('price')->orderBy('id', 'DESC')->first();
        if ($result) {
            return $result->price;
        } else {
            $result = TradePairs::where('id', $id)->select('last_price')->first();
            return $result->last_price;
        }
    }

    public static function priceRange($pair)
    {
        $color = "identical";
        $query = OrderTemp::where('pair', $pair)->select('askPrice')->where('cancel_id', NULL)->orderBy('id', 'desc')->limit(2)->get();
        if (!$query->isEmpty()) {
            $i = 0;
            foreach ($query as $open_order) {
                $j = $i + 1;
                if (isset($query[$j])) {
                    $preActivePrice = $query[$j]->askPrice;
                } else {
                    return $color;
                }
                $activePrice = $open_order->askPrice;
                if ($activePrice == $preActivePrice) {
                    return $color;
                }
                $color = ($activePrice < $preActivePrice) ? "negVal" : "posVal";
                break;
            }
        }
        return $color;
    }

    public static function depth($symbol)
    {
        return self::request("v3/depth", ["symbol" => $symbol]);
    }

    protected static function request($url, $params = [])
    {
        $headers[] = "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)";
        $query = http_build_query($params, '', '&');
        $base = "https://api.binance.com/api/";
        return json_decode(self::http_request($base . $url . '?' . $query, $headers), true);
    }

    protected static function http_request($url, $headers, $data = array(), $method = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($data) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if ($method == "DELETE") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        $content = curl_exec($ch);
        $info = curl_getinfo($ch);
        if ($info['http_code'] != 200) {
            $content = false;
        }

        if (curl_errno($ch)) {
            $content = false;
        }
        curl_close($ch);
        return $content;
    }

    public static function getCurrencyImage($symbol)
    {
        return Coin::where('symbol', $symbol)->select('image')->first()->image ?? '';
    }
}
