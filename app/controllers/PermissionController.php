<?php

class PermissionController extends BaseController {

  public function index()
  {
    $permissions = Permission::all();
    return $permissions;
  }

}
