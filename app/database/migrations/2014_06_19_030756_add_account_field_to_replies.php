<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountFieldToReplies extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum_thread_replies', function (Blueprint $table) {
            $table->integer('account_id')->unsigned()->after('id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forum_thread_replies', function (Blueprint $table) {
            $table->dropForeign('forum_thread_replies_account_id_foreign');
            $table->dropColumn('account_id');
        });
    }

}
