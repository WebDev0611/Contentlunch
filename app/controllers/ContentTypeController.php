<?php

class ContentTypeController extends BaseController {

  public function index()
  {
    return ContentType::all();
  }

}
