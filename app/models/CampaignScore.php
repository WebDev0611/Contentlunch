<?php

    use LaravelBook\Ardent\Ardent;

class CampaignScore extends Ardent {

    public $table = 'campaign_scores';

    public $softDelete = true;

    protected $fillable = [
        'date',
        'campaign_id'
    ];

    public static $rules = [
        'campaign_id' => 'required',
        'date' => 'required',
        'score' => 'required'
    ];

}