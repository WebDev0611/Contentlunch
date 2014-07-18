<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConnectionsAddPromote extends Migration {

	/**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    
    // Add column visible that indicates if content type
    // shows in the UI
    Schema::table('content_types', function ($table) {
      $table->boolean('visible')->default(true);
    });
    $types = ContentType::all();
    $list = require __DIR__ .'/../seeds/contentTypesList.php';

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
      $type = ContentType::where('key', $item[1])->first();
      $mode = 'update';
      if ( ! $type) {
        $mode = 'create';
        $type = new ContentType;
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

    // Add category to connections table
    Schema::table('connections', function ($table) {
      $table->string('category');
    });

    $connections = Connection::all();
    $list = require __DIR__ .'/../seeds/connectionsList.php';
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
      $connection = Connection::where('provider', $item[1])->first();
      $mode = 'update';
      if ( ! $connection) {
        $mode = 'create';
        $connection = new Connection;
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

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('content_types', function ($table) {
      $table->dropColumn('visible');
    });
     Schema::table('connections', function ($table) {
      $table->dropColumn('category');
    });
  }

}
