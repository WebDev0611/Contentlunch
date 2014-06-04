<?php

use LaravelBook\Ardent\Ardent;

class AccountContentSettings extends Ardent {

  protected $table = 'account_content_settings';

  protected $serialize = array('include_name', 'allow_edit_date', 'keyword_tags', 'persona_columns', 'personas');

  public static $rules = array(
    'account_id' => 'required'
  );

  public function beforeSave()
  {
    if ( ! empty($this->serialize)) {
      foreach ($this->serialize as $field) {
        if (is_array($this->$field)) {
          $this->$field = serialize($this->$field);
        }
      }
    }
    return true;
  }

  public function toArray()
  {
    $values = parent::toArray();
    if ( ! empty($this->serialize)) {
      foreach ($this->serialize as $field) {
        if ( ! is_array($values[$field])) {
          $values[$field] = unserialize($values[$field]);
        }
      }
    }
    return $values;
  }

  //protected function getDateFormat()
  //{
  //  return 'Y-m-d H:i:s';
  //}

}
