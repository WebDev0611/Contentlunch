<?php namespace Launch;

use Illuminate\Database\Migrations\Migration as IllMigration;

class Migration extends IllMigration {

  protected function note($note)
  {
    if (app()->environment() != 'testing') {
      print $note . PHP_EOL;
    }
  }

  protected function upPermissions()
  {
    foreach ($this->permissions as $permission) {
      list($name, $displayName, $assignRoles) = $permission;
      // Permission keys start with moduleName_
      $parts = explode('_', $name);
      list($module, $type) = $parts;
      $p = new \Permission;
      $p->name = $name;
      $p->display_name = $displayName;
      $p->module = $module;
      $p->type = $type;
      $p->save();
      // Attach this permission to roles
      if ($assignRoles) {
        foreach ($assignRoles as $roleName) {
          // Assign permission to all account roles as well as built-in roles
          $roles = \Role::where('name', $roleName)->get();
          foreach ($roles as $role) {
            $role->perms()->attach($p->id);
          }
        }
      }
    }
  }

  protected function downPermissions()
  {
    $roles = \Role::all();
    foreach ($this->permissions as $permission) {
      list($name, $displayName, $assignRoles) = $permission;
      $p = \Permission::where('name', $name)->first();
      // Detach permission from roles
      foreach ($roles as $role) {
        $role->perms()->detach($p->id);
      }
      // Delete permission
      $p->delete();
    }
  }

  protected function syncContentTypes()
  {
    $types = \ContentType::all();
    $list = require __DIR__ .'/../../database/seeds/contentTypesList.php';

    // Delete any content types from the database
    // that don't exist in the list
    foreach ($types as $type) {
      $exists = false;
      foreach ($list as $item) {
        if ($item[1] == $type->key) {
          $exists = true;
        }
      }
      if ( ! $exists) {
        $type->delete();
      }
    }

    // Sync the database with the connections list
    foreach ($list as $item) {
      $type = \ContentType::where('key', $item[1])->first();
      $mode = 'update';
      if ( ! $type) {
        $mode = 'create';
        $type = new \ContentType;
      }

      $type->name = $item[0];
      $type->key = $item[1];
      $type->base_type = $item[2];
      $type->visible = $item[3];

      if ($mode == 'create') {
        $type->save();
      } else {
        $type->updateUniques();
      }
    }
  }

  protected function syncConnections()
  {
    $connections = \Connection::all();
    $list = require __DIR__ .'/../../database/seeds/connectionsList.php';
    // Delete any connections from the database
    // that don't exist in the list
    foreach ($connections as $connection) {
      $exists = false;
      foreach ($list as $item) {
        if ($item[1] == $connection->provider) {
          $exists = true;
        }
      }
      if ( ! $exists) {
        $connection->delete();
      }
    }
    // Sync the database with the connections list
    foreach ($list as $item) {
      $connection = \Connection::where('provider', $item[1])->first();
      $mode = 'update';
      if ( ! $connection) {
        $mode = 'create';
        $connection = new \Connection;
      }

      $connection->name = $item[0];
      $connection->provider = $item[1];
      $connection->type = $item[2];
      $connection->category = $item[3];

      if ($mode == 'create') {
        $connection->save();
      } else {
        $connection->updateUniques();
      }
    }
  }

}