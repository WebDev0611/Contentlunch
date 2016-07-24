<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

    protected $hidden = [ 'id', 'created_at', 'updated_at'];
    protected $fillable = ['tag'];
}