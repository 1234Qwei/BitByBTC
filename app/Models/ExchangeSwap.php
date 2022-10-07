<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExchangeDeposit;
use App\Models\User;
use App\Models\Coin;

class ExchangeSwap extends Model
{
    use HasFactory;

    protected $table = 'exchange_swap';

    public function swapTo()
    {
        return $this->hasOne(Coin::class, 'coin_id', 'swap_to');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
 
    public function swapFrom()
    {
        return $this->hasOne(Coin::class, 'coin_id', 'swap_from');
    }

    public function withdrawBalance($user_id = 0, $coin = '')
    {
        $withdraw = \DB::select('SELECT sum(amount) as balance FROM `withdraw-request` WHERE status != 3 AND user_id = ? AND `coin` = ?', [$user_id, $coin]);

        $withdrawBalance = $withdraw[0]->balance ?? 0;

        return $withdrawBalance;
    }

    public function availbleCoinBalance($user_id = 0, $coin = '')  
    {
        $exchangeDeposit = new ExchangeDeposit;
        $exchangeDepositBalance = $exchangeDeposit->availbleCoinBalance($user_id, $coin);
        $withdrawBalance = $this->withdrawBalance($user_id, $coin);
        echo $exchangeDepositBalance;
        return $withdrawBalance + $exchangeDepositBalance;
    }

    public function availbleCoinBalanceByAdmin($coin = '')
    {
        $deposit = \DB::select('SELECT sum(deposit_coin) as balance FROM `exchange_deposit` WHERE status = 1 AND `deposit_currency` = ?', [$coin]);
        $depositBalance = $deposit[0]->balance;

        // $withdrawBalance = $this->withdrawBalance($user_id, $coin);
        $swapDepositBalance = ($depositBalance);
        return  $swapDepositBalance;
    }

    public function withdrawBalanceByAdmin($user_id = 0, $coin = '')
    {
        $withdraw = \DB::select('SELECT sum(amount) as balance FROM `withdraw-request` WHERE status != 3 AND `coin` = ?', [$coin]);

        $withdrawBalance = $withdraw[0]->balance ?? 0;

        return $withdrawBalance;
    }

    public function availbleCoinBalanceForAdmin($coin = '')
    {
        $exchangeDepositBalance = $this->availbleCoinBalanceByAdmin($coin);
        $withdrawBalance = $this->withdrawBalanceByAdmin($coin);
        return $withdrawBalance + $exchangeDepositBalance;
    }
}
