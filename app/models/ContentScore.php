<?php

    use LaravelBook\Ardent\Ardent;

class ContentScore extends Ardent {

    public $table = 'content_scores';

    public $softDelete = true;

    protected $fillable = [
        'date',
        'content_id'
    ];

    public static $rules = [
        'content_id' => 'required',
        'date' => 'required',
        'score' => 'required'
    ];

    public function content()
    {
        return $this->belongsTo('Content');
    }

}