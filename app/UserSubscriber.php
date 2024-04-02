<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubscriber extends Model
{
    protected $fillable = [
        'order_id',
        'name',
        'email',
        'card_number',
        'card_exp_month',
        'card_exp_year',
        'subscription',
        'subscription_id',
        'price',
        'price_currency',
        'txn_id',
        'payment_status',
        'payment_type',
        'receipt',
        'user_id',
    ];

    public static function total_subscriber()
    {
        return UserSubscriber::count();
    }

    public static function total_subscriber_price()
    {
        return UserSubscriber::sum('price');
    }

    public function total_voucher_used()
    {
        return $this->hasOne('App\UserVoucher', 'order', 'order_id');
    }
}
