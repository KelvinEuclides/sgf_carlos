<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstimationItem extends Model
{
    protected $fillable = [
        'estimation_id',
        'item',
        'quantity',
        'tax',
        'discount',
        'price',
        'description',
    ];

    public function items()
    {
        return $this->hasOne('App\Item', 'id', 'item');
    }
}
