<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{

    protected $fillable = [
        'name',
        'code',
        'discount',
        'limit',
        'description',
    ];


    public function used_voucher()
    {
        return $this->hasMany('App\UserVoucher', 'voucher', 'id')->count();
    }
}
