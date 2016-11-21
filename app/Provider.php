<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model {

	protected $hidden = [ 'created_at', 'updated_at'];
	protected $fillable = ['name', 'slug', 'type', 'user_id'];

	public function contentType()
	{
		return $this->hasOne('App\ContentType');
	}

	public function connections()
	{
		return $this->hasMany('App\Connection');
	}

	public static function findBySlug($slug)
	{
		return self::where('slug', '=', $slug)->firstOrFail();
	}
}