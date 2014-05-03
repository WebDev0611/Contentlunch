<?php

use LaravelBook\Ardent\Ardent;

class AccountConnection extends Ardent {

  protected $table = 'account_connections';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'name', 'status'
  ];

  public static $rules = [
    'account_id' => 'required',
    'connection_id' => 'required',
    'name' => 'required',
    'status' => 'required'
  ];

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

  public static function doQuery($accountID, $type = null)
  {
    $query = DB::table('account_connections')
      ->join('connections', 'account_connections.connection_id', '=', 'connections.id');
    if ($type) {
      $query->where('connections.type', $type);
    }
    $connections = $query->get([
      'account_connections.id',
      'account_connections.name',
      'account_connections.status',
      'account_connections.created_at',
      'account_connections.updated_at',
      'account_connections.settings',
      'account_connections.account_id',
      'connections.id AS connection_id',
      'connections.name AS connection_name',
      'connections.provider AS connection_provider'
    ]);
    if ($connections) {
      foreach ($connections as $connection) {
        $connection->settings = unserialize($connection->settings);
      }
    }
    return $connections;
  }

}
