<?php

use LaravelBook\Ardent\Ardent;

class CampaignTask extends Ardent {

    protected $table = 'campaign_tasks';

    public $autoHydrateEntityFromInput = true;

    public $forceEntityHydrationFromInput = true;

    protected $fillable = [
        'id',
        'campaign_id',
        'user_id',
        'name',
        'due_date',
        'date_completed',
        'is_complete',
    ];

    public static $rules = [
        'campaign_id' => 'required',
        'user_id'     => 'required',
        'name'        => 'required',
        // 'due_date'    => 'required',
    ];

    public function campaign()
    {
        return $this->belongsTo('Campaign');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

}