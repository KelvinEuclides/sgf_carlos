<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    protected $fillable = [
        'user',
        'voucher',
    ];

    public function userDetail()
    {
        return $this->hasOne('App\User', 'id', 'user');
    }

    public function voucher_detail()
    {
        return $this->hasOne('App\Voucher', 'id', 'voucher');
    }
}
