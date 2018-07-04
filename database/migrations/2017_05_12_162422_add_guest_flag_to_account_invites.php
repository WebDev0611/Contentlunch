<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGuestFlagToAccountInvites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_invites', function (Blueprint $table) {
            $table->boolean('is_guest')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_invites', function (Blueprint $table) {
            $table->dropColumn([ 'is_guest' ]);
        });
    }
}
