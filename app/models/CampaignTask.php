<?php

use LaravelBook\Ardent\Ardent;

class CampaignTask extends Ardent {

    protected $table = 'campaign_tasks';

    public $autoHydrateEntityFromInput = true;

    public $forceEntityHydrationFromInput = true;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
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

    protected function beforeSave()
    {
        $this->due_date = date('Y-m-d', strtotime($this->due_date));
    }
}