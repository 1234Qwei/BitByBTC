<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinOrder extends Model
{
    use HasFactory;

    protected $table = 'coin_order';

    protected $guarded = [];
}
