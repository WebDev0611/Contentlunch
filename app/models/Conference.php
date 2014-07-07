<?php

use LaravelBook\Ardent\Ardent;

class Conference extends Ardent {

  public $autoHydrateEntityFromInput = true;
  public $forceEntityHydrationFromInput = true;

  protected $table = 'conferences';

  public static $rules = [
    'description' => 'required',
    'status' => 'required',
    'topic' => 'required',
    'consultant' => 'required',
    'user_id' => 'required',
    'account_id' => 'required'
  ];

  protected $fillable = [
    'description', 'topic', 'consultant'
  ];

  public function user()
  {
    return $this->belongsTo('User')->with('image');
  }

  protected function beforeSave()
  {
    if (is_array(@$this->tokens)) {
      $this->tokens = json_encode($this->tokens);
    }
  }

  public function toArray()
  {
    $values = parent::toArray();

    if (is_string(@$values['tokens'])) {
      $values['tokens'] = @json_decode($values['tokens'], true);
    }
    if (!@$values['tokens']) {
      $values['tokens'] = [];
    }

    return $values;
  }

}