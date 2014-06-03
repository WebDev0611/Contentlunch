<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GuestCollaboratorsType extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest_collaborators', function (Blueprint $table) {
            // renaming the column to settings to be more 
            // consitent with other uses of this field
            $table->renameColumn('token', 'settings');
            $table->enum('type', ['group', 'individual'])->after('name')->default('individual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guest_collaborators', function (Blueprint $table) {
            $table->renameColumn('settings', 'token');
            $table->dropColumn('type');
        });
    }

}
