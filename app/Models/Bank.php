<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'banks';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function findOrCreate($id)
    {
        $obj = static::find($id);
        return $obj ?: new static;
    }

    public function accountType()
    {
        return $this->hasOne(BankMaster::class, 'id', 'selected_account');
    }
}
