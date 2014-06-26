<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountIdToGuests extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest_collaborators', function (Blueprint $table) {
            $table->integer('account_id')->unsigned()->after('content_id');
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
            $table->dropColumn('account_id');
        });
    }

}
