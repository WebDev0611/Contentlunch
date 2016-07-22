<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model {

    protected $hidden = [ 'created_at', 'updated_at'];
    protected $fillable = ['name', 'slug', 'type', 'user_id'];


    public function connection()
    {
        return $this->belongsTo('App\Connection');
    }
}