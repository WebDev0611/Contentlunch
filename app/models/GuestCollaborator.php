<?php namespace Models;

use LaravelBook\Ardent\Ardent;

class GuestCollaborator extends Ardent {

    public $autoHydrateEntityFromInput = false;
    public $forceEntityHydrationFromInput = false;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public static $rules = [
        'connection_user_id' => 'required',
        'name'               => 'required',
        'connection_id'      => 'required',
        'content_id'         => 'required',
    ];

    public function content()
    {
        return $this->belongsTo('content');
    }

    public function connection()
    {
        return $this->belongsTo('connection');
    }
}
