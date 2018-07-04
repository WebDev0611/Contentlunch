<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsPolymorphicRelationshipToAccountInvites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_invites', function (Blueprint $table) {
            $table->integer('inviteable_id')->unsigned()->nullable();
            $table->string('inviteable_type')->nullable();
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
            $table->dropColumn([ 'inviteable_id', 'inviteable_type' ]);
        });
    }
}
