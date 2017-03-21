<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentStatus extends Model
{
    protected $table = 'content_status';

    const BEING_WRITTEN = 1;
    const READY = 2;
    const PUBLISHED = 3;
    const ARCHIVED = 4;
}
