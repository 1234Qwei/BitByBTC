<?php

namespace App\Models\Stacking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class TransactionHistory extends Model
{
    use HasFactory;

    protected $table = 'transaction_history';

    protected $fillable = ['user_id', 'amount', 'transaction_type', 'bonus_id', 'withdraw_id', 'deposit_id'];

    public function withdraw()
    {
        return $this->hasOne(WithdrawRequest::class, 'id', 'withdraw_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
