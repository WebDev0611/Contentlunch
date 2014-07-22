<?php

use Illuminate\Database\Schema\Blueprint;
use Launch\Migration;

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
    
    $this->syncContentTypes();

    // Add category to connections table
    Schema::table('connections', function ($table) {
      $table->string('category');
    });

    $this->syncConnections();
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
