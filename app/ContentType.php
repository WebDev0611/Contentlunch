<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentType extends Model {

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'provider_id'
    ];

    public function provider()
    {
        return $this->belongsTo('App\Provider');
    }

    public function contents()
    {
        return $this->hasMany('App\Content');
    }

    // sure we can add variables to make it more dynamic
    //  --
    public static function dropdown()
    {
        $contentTypeOptions = ['' => '-- Select Content Type --'];
        $contentTypeOptions += ContentType::select('id','name')
            ->where('provider_id', '!=', '0')
            ->orderBy('name', 'asc')
            ->distinct()
            ->lists('name', 'id')
            ->toArray();

        return $contentTypeOptions;
    }

    public function __toString()
    {
        return $this->name;
    }
}