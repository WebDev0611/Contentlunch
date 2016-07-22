<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model {

    protected $hidden = [ 'created_at', 'updated_at'];
    protected $fillable = ['name', 'provider_id', 'active', 'user_id', 'settings'];


    public function provider()
    {
        return $this->hasOne('App\Provider');
    }
}