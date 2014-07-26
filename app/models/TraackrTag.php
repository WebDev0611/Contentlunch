<?php

use LaravelBook\Ardent\Ardent;

class TraackrTag extends Ardent {
    public $autoHydrateEntityFromInput    = false;
    public $forceEntityHydrationFromInput = false;

    protected $fillable = [
        'content_id',
        'campaign_id',
        'account_id',
        'user',
        'traackr_id',
    ];

    public static $rules = [
        'account_id' => 'required',
        'user'       => 'required',
        'traackr_id' => 'required',
    ];

    protected function beforeSave()
    {
        if (is_array(@$this->user)) {
            $this->user = json_encode($this->user);
        }
    }

    public function toArray()
    {
        $values = parent::toArray();

        if (is_string(@$values['user'])) {
            $values['user'] = @json_decode($values['user'], true);
        }
        if (!@$values['user']) {
            $values['user'] = [];
        }

        if (is_string(@$values['users'])) {
            $values['users'] = @json_decode($values['users'], true);
            unset($values['user']);
        }

        return $values;
    }

}