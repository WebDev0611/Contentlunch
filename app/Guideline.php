<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guideline extends Model
{
    protected $fillable = [
        'account_id',
        'publishing_guidelines',
        'company_strategy',
    ];

    public function account()
    {
        return $this->belongsTo('App\Account');
    }
}
