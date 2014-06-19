<?php

use LaravelBook\Ardent\Ardent;

class ContentTaskGroup extends Ardent {

    public $autoHydrateEntityFromInput = false;
    public $forceEntityHydrationFromInput = false;

    protected $fillable = [
      'due_date',
      'date_completed',
      'is_complete'
    ];

    // Trying $fillable instead of $guarded so extra
    // inputs don't get set on the model (like the url path)
    /*
    protected $guarded = [
        'id',
        'status',
        'content_id',
        'created_at',
        'updated_at',
        'tasks',
    ];
    */

    public static $rules = [
        'content_id' => 'required',
        'status'     => 'required',
        'due_date'   => 'required',
    ];

    public function content()
    {
        return $this->belongsTo('Content');
    }

    public function tasks()
    {
        return $this->hasMany('ContentTask');
    }

}
