<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    use HasFactory;
    protected $table = 'withdraw-request';
    protected $fillable = ['fee_amount', 'final_amount', 'bank_id', 'coin', 'coin_id', 'user_id', 'amount', 'remarks', 'created_by', 'withdrawal_date', 'wallet_address', 'transaction_hash', 'is_withdraw_fee_checked', 'stacking_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function crypto()
    {
        return $this->hasOne(Coin::class, 'id', 'coin_id');
    }
}
