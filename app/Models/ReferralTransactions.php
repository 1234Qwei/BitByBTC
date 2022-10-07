<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralTransactions extends Model
{
    use HasFactory;

    protected $table = 'referal_transaction';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function refferer()
    {
        return $this->hasOne(User::class, 'id', 'refferer_id');
    }

    public function coin()
    {
        return $this->hasOne(Coin::class, 'id', 'coin_id');
    }
}
