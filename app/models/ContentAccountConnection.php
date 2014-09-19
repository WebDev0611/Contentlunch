<?php

    use LaravelBook\Ardent\Ardent;

class ContentAccountConnection extends Ardent {

    public $table = 'content_account_connections';

    protected $fillable = [
        'content_id',
        'account_connection_id'
    ];

    public static $rules = [
        'content_id' => 'required',
        'account_connection_id' => 'required',
    ];

    public function content()
    {
        return $this->belongsTo('Content');
    }

    public function account_connection() {
        return $this->belongsTo('AccountConnection');
    }

}