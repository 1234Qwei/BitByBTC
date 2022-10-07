<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Passport\HasApiTokens;
use App\Models\SellOrder;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'referred_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function referredBy()
    {
        return $this->hasOne(User::class, 'username', 'referred_by');
    }

    public function findForPassport($identifier)
    {
        return $this->orWhere('email', $identifier)->orWhere('username', $identifier)->first();
    }

    public function verification()
    {
        return $this->hasOne(ConsumerVerification::class, 'user_id', 'id');
    }
    public function getSellOrderInfo()
    {
        return $this->hasOne(SellOrder::class, 'user_id', 'id');
    }
    public function getUserSellOrderCount($id)
    {
        $array['totalCount'] = SellOrder::where('user_id',$id)->count();

        $array['lastThirtydays'] = SellOrder::where('user_id',$id) ->where('created_at', '>', now()->subDays(30)->endOfDay())->count();

        $completedCount = SellOrder::where('user_id',$id)->where('status',3)->count();

        $array['completionRate'] = ($completedCount / $array['lastThirtydays']) * 100;

        return $array;
    }
}
