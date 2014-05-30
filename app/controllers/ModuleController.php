<?php

class ModuleController extends BaseController {
  
  public function index()
  {
    return Module::all();
  }

}