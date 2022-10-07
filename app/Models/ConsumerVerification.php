<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumerVerification extends Model
{
    use HasFactory;

    protected $table = 'verification';
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id');
    }
}
