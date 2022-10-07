<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id'
    ];
	public function getbankname(){
		
		return $this->belongsTo(Bank::class, 'bank_id');
	}
	public static function getbankdetail($userid,$bankid){
		
        $userbanks = Bank::where('user_id', $userid)->where('id', $bankid)->first();
		
        return $userbanks;
		
	}
}
