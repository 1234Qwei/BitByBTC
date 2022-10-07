<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoggedInHistory extends Model
{
    use HasFactory;

    protected $table = 'loggedin_history';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
