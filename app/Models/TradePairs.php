<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradePairs extends Model
{
    use HasFactory;

    protected $table = 'trade_pair';

    public static function getFullPairs()
    {
        return self::select('id', 'from_symbol', 'to_symbol', 'trade_fee', 'min_price')->get();
    }
}
