<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'account',
        'customer',
        'category',
        'reference',
        'description',
        'created_by',
    ];

    public function categories()
    {
        return $this->hasOne('App\Category', 'id', 'category');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'customer');
    }

    public function bankAccount()
    {
        return $this->hasOne('App\BankAccount', 'id', 'account');
    }
}
