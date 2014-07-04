<?php

use LaravelBook\Ardent\Ardent;

class LaunchResponse extends Ardent {
  
  protected $table = 'launches';

  protected $fillable = [
    'content_id', 'account_connection_id', 'success', 'response'
  ];

  public function toArray()
  {
    $values = parent::toArray();
    if ( ! is_array($values['response'])) {
      $values['response'] = unserialize($values['response']);
    }
    return $values;
  }

}