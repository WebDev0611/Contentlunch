<?php

use LaravelBook\Ardent\Ardent;

class AccountConnection extends Ardent {

  protected $table = 'account_connections';

  public static $rules = array(
    'account_id' => 'required',
    'type' => 'required',
    'name' => 'required',
    'status' => 'required'
  );

  public function beforeSave()
  {
    if (is_array($this->settings)) {
      $this->settings = serialize($this->settings);
    }
    return true;
  }

  public function toArray()
  {
    $values = parent::toArray();
    if ( ! is_array($values['settings'])) {
      $values['settings'] = unserialize($values['settings']);
    }
    return $values;
  }

}
