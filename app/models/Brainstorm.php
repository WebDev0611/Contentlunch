<?php

use LaravelBook\Ardent\Ardent;

class Brainstorm extends Ardent {
    protected $table = 'brainstorms';

    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable = [
        'content_id',
        'campaign_id',
        'account_id',
        'agenda',
        'date',
        'time',
        'description',
        'credentials',
    ];

    public static $rules = [
        'content_id'  => 'required',
        'campaign_id' => 'required',
        'account_id'  => 'required',
        'agenda'      => 'required',
        'date'        => 'required',
        'time'        => 'required',
        'description' => 'required',
        'credentials' => 'required',
    ];

    public function validate(array $rules = [], array $customMessages = []) {
      // merge any custom rules with our standard rules
      $rules = array_merge(self::$rules, $rules);
      
      // only one or the other content_id or campaign_id is required
      if ($this->campaign_id) {
        unset($rules['content_id']);
      }
      if ($this->content_id) {
        unset($rules['campaign_id']);
      }

      return parent::validate($rules , $customMessages);
    }

    public function content()
    {
        return $this->belongsTo('Content');
    }

    public function campaign()
    {
        return $this->belongsTo('Campaign');
    }

}