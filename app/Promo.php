<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'content_orders_promotions';


    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
