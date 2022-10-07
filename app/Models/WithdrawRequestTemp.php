<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawRequestTemp extends Model
{
    use HasFactory;
    protected $table = 'withdraw-request-temp';
    protected $fillable = [ 'fee_amount', 'final_amount', 'coin_id','user_id', 'otp', 'amount', 'remarks', 'created_by', 'withdrawal_date', 'wallet_address'];

    public function user() {
    	return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function coin() {
        return $this->hasOne(Coin::class, 'id', 'coin_id');
    }    
}
