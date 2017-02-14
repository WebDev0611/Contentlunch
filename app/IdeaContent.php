<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdeaContent extends Model
{
    protected $table = 'idea_content';
    public $fillable = [
        'author',
        'body',
        'fb_shares',
        'google_shares',
        'image',
        'link',
        'source',
        'title',
        'total_shares',
        'tw_shares',
        'idea_id',
        'user_id',
    ];
}
