<?php

namespace App;

use App\Presenters\ContentMessagePresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class ContentMessage extends Model
{
    use PresentableTrait;

    protected $presenter = ContentMessagePresenter::class;

    public $table = 'content_messages';
    public $fillable = [
        'sender_id', 'content_id', 'body',
    ];

    public function content()
    {
        return $this->belongsTo('App\Content');
    }

    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }

}
