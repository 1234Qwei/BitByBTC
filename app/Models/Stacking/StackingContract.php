<?php

namespace App\Models\Stacking;
 
use Illuminate\Database\Eloquent\Model;
use App\Models\Stacking\Packages; 
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class StackingContract extends Model 
{
    use SoftDeletes; 

    protected $table = 'stacking-contract';

    protected $fillable = ['package_id', 'asset_id', 'user_id', 'term_id', 'billing_no', 'date', 'expiry_date'];

    public function package()
    {
        return $this->hasOne(Packages::class, 'id', 'package_id');
    }

    public function term()
    {
        return $this->hasOne(PackageTerm::class, 'id', 'term_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
