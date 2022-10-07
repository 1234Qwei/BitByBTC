<?php

namespace App\Models\Stacking; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    use HasFactory;
    protected $table = 'packages';

    public function asset()
    {
        return $this->hasOne(\App\Models\Coin::class, 'id', 'asset_id');
    }
}
