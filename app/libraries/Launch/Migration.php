<?php namespace Launch;

use Illuminate\Database\Migrations\Migration as IllMigration;

class Migration extends IllMigration {

  protected function note($note)
  {
    if (app()->environment() != 'testing') {
      print $note . PHP_EOL;
    }
  }

}