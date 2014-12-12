<?php

class ConnectionController extends BaseController {

  public function index()
  {
  	$query = Connection::query();
    if (Input::get('type')) {
      $query->where('type', Input::get('type'));
    }
    // These connections shouldn't be available yet
    $query->where('enabled', 1)
      ->where('provider', '!=', 'outbrain');
    return $query->get();
  }

}
