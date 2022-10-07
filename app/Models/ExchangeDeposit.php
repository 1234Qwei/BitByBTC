<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Coin;

class ExchangeDeposit extends Model
{
    use HasFactory;

    protected $table = 'exchange_deposit'; 

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
 
    public function deposit()
    {
        return $this->hasOne(Coin::class, 'coin_id', 'deposit_currency');
    }

    public function bank() 
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }

    public function withdrawBalance($user_id = 0, $coin = '')
    {
        $withdraw = \DB::select('SELECT sum(amount) as balance FROM `withdraw-request` WHERE status != 3 AND user_id = ? AND `coin` = ?', [$user_id, $coin]);

        $withdrawBalance = $withdraw[0]->balance ?? 0;

        return $withdrawBalance;
    }

    public function monthDepositBalance($user_id)
    {
        $query = \DB::select('SELECT sum(`deposit_coin`) as monthBalance FROM `exchange` WHERE `exchange_type`=1 AND `status` != 2 AND MONTH(`created_at`) = MONTH(CURRENT_DATE()) AND user_id = ' . $user_id);
        return $query ? $query[0]->monthBalance : 0;
    }

    public function availbleCoinBalance($user_id, $coin = '')
    {
        $deposit = \DB::select('SELECT sum(deposit_coin) as balance FROM `exchange_deposit` WHERE status = 1 AND user_id = ? AND `deposit_currency` = ?', [$user_id, $coin]);
        $sellorder = \DB::select('SELECT sum(coin_volume) as balance FROM `sell_orders` WHERE status = 1 AND user_id = ? AND `deposit_currency` = ?', [$user_id, $coin]);
        $sellBalance = $sellorder[0]->balance;
        $depositBalance = $deposit[0]->balance;
        $swapDepositBalance = ($depositBalance - $sellBalance);
        return  $swapDepositBalance;
    }
}
