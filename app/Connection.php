<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model {

    protected $hidden = [ 'provider_id', 'user_id', 'created_at', 'updated_at'];
    protected $fillable = ['name', 'provider_id', 'active', 'user_id', 'settings'];


    public function provider()
    {
        return $this->belongsTo('App\Provider', 'provider_id');
    }
    
    public function contents()
    {
       return $this->hasMany('App\Content');
    }
}