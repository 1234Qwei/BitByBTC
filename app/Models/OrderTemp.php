<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTemp extends Model
{
    use HasFactory;

    protected $table = 'tmp_order';

    protected $guarded = [];
}
