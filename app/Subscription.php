<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration',
        'max_users',
        'max_customers',
        'max_vendors',
        'description',
        'image',
    ];

    public static $arrDuration = [
        'unlimited' => 'Unlimited',
        'month' => 'Per Month',
        'year' => 'Per Year',
    ];

    public static function total_subscription()
    {
        return Subscription::count();
    }

    public static function most_purchase_subscription()
    {
        $free_subscription = Subscription::where('price', '<=', 0)->first()->id;

        return User:: select(\DB::raw('count(*) as total'))->where('type', '=', 'company')->where('subscription', '!=', $free_subscription)->groupBy('subscription')->first();
    }
}
