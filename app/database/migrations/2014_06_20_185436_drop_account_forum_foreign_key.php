<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAccountForumForeignKey extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropForeign('forum_threads_account_id_foreign');
            $table->dropColumn('account_id');
        });
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->integer('account_id')->unsigned()->nullable()->after('id');
        });

        Schema::table('forum_thread_replies', function (Blueprint $table) {
            $table->dropForeign('forum_thread_replies_account_id_foreign');
            $table->dropColumn('account_id');
        });
        Schema::table('forum_thread_replies', function (Blueprint $table) {
            $table->integer('account_id')->unsigned()->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropColumn('account_id');
        });
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->integer('account_id')->unsigned()->after('id');
            // you'll need to drop the table if there's any data to reinstate this...
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::table('forum_thread_replies', function (Blueprint $table) {
            $table->dropColumn('account_id');
        });
        Schema::table('forum_thread_replies', function (Blueprint $table) {
            $table->integer('account_id')->unsigned()->after('id');
            // you'll need to drop the table if there's any data to reinstate this...
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

}
