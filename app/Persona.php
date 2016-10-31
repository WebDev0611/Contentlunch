<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    public $fillable = [ 'name', 'description' ];
}
