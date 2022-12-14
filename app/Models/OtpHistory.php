<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpHistory extends Model
{
    use HasFactory;
    
    protected $table = 'otp_history';
	
    protected $fillable = [
        'user_id',
        'otp',
        'status'
    ];
}
