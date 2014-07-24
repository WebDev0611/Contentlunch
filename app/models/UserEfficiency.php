<?php

use LaravelBook\Ardent\Ardent;

class UserEfficiency extends Ardent {
    
    public $table = 'measure_user_efficiency';

    public $timestamps = false;

    protected $guarded = ['id'];

}