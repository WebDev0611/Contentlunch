<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WriterAccessPartialOrder extends Model
{
    public $table = 'writer_access_partial_orders';

    protected $fillable = [
        'user_id',
        'project_name',
        'duedate',
        'asset_type_id',
        'wordcount',
        'writer_level',
        'content_title',
        'instructions',
        'narrative_voice',
        'target_audience',
        'tone_of_writing',
    ];

}
