<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GuestCollaboratorsTokens extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest_collaborators', function (Blueprint $table) {
            // serialized token object from OAuth class
            $table->text('token')->after('connection_user_id')->nullable();
            // access code used on CL's side to connect guest to URL send in DM
            $table->string('access_code')->after('connection_user_id');
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
            $table->dropColumn('token', 'access_code');
        });
    }

}
