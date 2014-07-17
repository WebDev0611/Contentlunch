<?php

use LaravelBook\Ardent\Ardent;

class MeasureCreatedContent extends Ardent {

    public $table = 'measure_created_content';

    public $autoHydrateEntityFromInput    = false;
    public $forceEntityHydrationFromInput = false;

	protected $guarded = [];

    public $incrementing = false;
    public $timestamps   = false;

    public function beforeSave($forced = true) 
    {
        if (is_array(@$this->stats)) {
            $this->stats = json_encode($this->stats);
        }
    }

    public function toArray()
    {
        $values = parent::toArray();

        if (is_string(@$values['stats'])) {
            $values['stats'] = @json_decode($values['stats'], true);
        }
        if (!@$values['stats']) {
            $values['stats'] = [];
        }

        return $values;
    }

}