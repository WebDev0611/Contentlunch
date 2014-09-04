<?php

use LaravelBook\Ardent\Ardent;
use Launch\Connections\API\ConnectionConnector;

class LaunchResponse extends Ardent {

    protected $softDelete = true;

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

  public function account_connection()
  {
    return $this->belongsTo('AccountConnection');
  }

  public function content()
  {
    return $this->belongsTo('Content');
  }

  public function getPermalink()
  {
    if (empty($this->account_connection->connection_id)) {
      return;
    }
    $connection = Connection::withTrashed()->find($this->account_connection->connection_id);
    $response = unserialize($this->response);
    switch ($connection->provider) {
      case 'blogger':
        return $response->getUrl();
      case 'soundcloud':
        return $response['permalink_url'];
      case 'tumblr':
        $api = ConnectionConnector::loadAPI('tumblr', $this->account_connection);
        $info = $api->getMe();
        return $info['user']['blogs'][0]['url'] . $response['response']['id'];
      case 'vimeo':
        return 'https://vimeo.com/' . $response['video_id'];
      case 'wordpress':
        return $response['URL'];
      case 'youtube':
        return 'https://www.youtube.com/watch?v=' . $response->getId();
    }
    
  }

}