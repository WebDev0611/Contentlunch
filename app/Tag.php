<?php

namespace App;

use App\Presenters\TagPresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Tag extends Model
{
    use PresentableTrait;

    protected $presenter = TagPresenter::class;
    protected $fillable = [ 'tag' ];

    public function contents()
    {
        return $this->belongsToMany('App\Content');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }
}
