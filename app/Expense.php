<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category',
        'description',
        'amount',
        'date',
        'vendor',
        'attachment',
        'created_by',
    ];

    public function categories()
    {
        return $this->hasOne('App\Category', 'id', 'category');
    }


    public function user()
    {
        return $this->hasOne('App\User', 'id', 'vendor');
    }
    public function bankAccount()
    {
        return $this->hasOne('App\BankAccount', 'id', 'account');
    }
}
