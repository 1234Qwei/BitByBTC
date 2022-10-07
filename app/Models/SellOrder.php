<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'id'
    ];
    public function getcoin()
    {
        return $this->hasOne(Coin::class, 'coin_id', 'coin_id');
    }
	public function getuserdetail(){
		
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
    public function getpaymentoption()
    {
        return $this->hasMany(UserBank::class, 'order_id', 'id');
    }
}
