<?php

use LaravelBook\Ardent\Ardent;

class UserEfficiency extends Ardent {
    
    public $table = 'measure_user_efficiency';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function toArray()
    {
        $values = parent::toArray();

        // convert float values from strings if not null
        $floats = [
            'converted_concepts',
            'completed_content_tasks',
            'completed_campaign_tasks',
            'early_content_tasks',
            'early_campaign_tasks',
        ];

        foreach($floats as $float) {
            if (!is_null($values[$float])) $values[$float] = floatval($values[$float]);
        }

        return $values;
    }

}