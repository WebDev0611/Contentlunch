<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskAdjustment extends Model
{
    protected $table = 'task_adjustments';

    protected $fillable = [
        'task_id',
        'user_id',
        'before',
        'after',
    ];
}
