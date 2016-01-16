<?php

use LaravelBook\Ardent\Ardent;

class Brainstorm extends Ardent {
    protected $table = 'brainstorms';

    public $softDelete = true;

    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable = [
        'user_id',
        'account_id',
        'agenda',
        'datetime',
        'description',
    ];

    public static $rules = [
        'user_id'     => 'required',
        'account_id'  => 'required'
    ];

    public function validate(array $rules = [], array $customMessages = [], array $customAttributes = array())
    {
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

    public function account() {
        return $this->belongsTo('Account');
    }

    protected function beforeSave()
    {
        if (is_array(@$this->agenda)) {
            $this->agenda = json_encode($this->agenda);
        }
        if (is_numeric($this->datetime)) {
            $this->datetime = date('Y-m-d H:i:s', $this->datetime);
        }

        if($this->id && $this->isDirty('datetime')) {
            $this->rescheduledEmail();
        }
    }

    protected function beforeDelete() {
        $this->cancellationEmail();
    }

    protected function beforeCreate() {
        $this->scheduledEmail();
    }

    public function cancellationEmail() {
        $emails = $this->account()->with('users')->first()->users->lists('email');

        Mail::send('emails.brainstorm.cancellation', ['brainstorm' => $this->toArray()], function($message) use ($emails) {
            $message->to($emails)->subject('Brainstorm Cancellation');
        });
    }

    public function scheduledEmail() {
        $emails = $this->account()->with('users')->first()->users->lists('email');

        if($this->content_id) {
            $concept = $this->content()->first()->toArray();
        }
        else if($this->campaign_id) {
            $concept = $this->campaign()->first()->toArray();
        }
        else {
            $concept = [];
        }

        Mail::send('emails.brainstorm.scheduled', ['brainstorm' => $this->toArray(), 'concept' => $concept], function($message) use ($emails) {
            $message->to($emails)->subject('Brainstorm Scheduled');
        });
    }

    public function rescheduledEmail() {
        $emails = $this->account()->with('users')->first()->users->lists('email');

        if($this->content_id) {
            $concept = $this->content()->first()->toArray();
        }
        else if($this->campaign_id) {
            $concept = $this->campaign()->first()->toArray();
        }
        else {
            $concept = [];
        }

        Mail::send('emails.brainstorm.rescheduled', ['brainstorm' => $this->toArray(), 'concept' => $concept], function($message) use ($emails) {
            $message->to($emails)->subject('Brainstorm Rescheduled');
        });
    }

    public function toArray()
    {
        $values = parent::toArray();

        if (is_string(@$values['agenda'])) {
            $values['agenda'] = @json_decode($values['agenda'], true);
        }
        if (!@$values['agenda']) {
            $values['agenda'] = [];
        }

        return $values;
    }

}