<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {
    public function subscriptionType () {
        return $this->belongsTo('App\subscriptionType');
    }

    public function account () {
        return $this->belongsTo('App\Account');
    }
}
