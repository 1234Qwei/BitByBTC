<?php

namespace App\Models\Stacking; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusAmount extends Model
{
    use HasFactory; 

    protected $table = 'bonus_amount';

    protected $fillable = ['user_id', 'amount', 'payment_type', 'status', 'stacking_id', 'created_date'];

    public function stacking()
    {
        return $this->hasOne(StackingContract::class, 'id', 'stacking_id');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}
