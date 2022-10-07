<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    use HasFactory;
    
    protected $table = 'order_requests';
	
    public function getRequestedCoinDetails()
    {
        return $this->hasMany(SellOrder::class, 'id', 'order_id');
    }
    public function getUserDetails()
    {
        return $this->hasOne(User::class, 'id', 'from_user_id');
    }
    public function getRequestedCoinDetailsOne()
    {
        return $this->hasOne(SellOrder::class, 'id', 'order_id');
    }	
	
}
