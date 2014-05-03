<?php

class ConnectionController extends BaseController {

  public function index()
  {
    if (Input::get('type')) {
      return Connection::where('type', Input::get('type'))->get();
    }
    return Connection::all();
  }

}
