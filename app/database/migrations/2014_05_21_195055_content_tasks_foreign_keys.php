<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentTasksForeignKeys extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_task_groups', function (Blueprint $table) {
            $table->dropColumn('content_id');
        });

        Schema::table('content_tasks', function (Blueprint $table) {
            $table->dropColumn('content_task_group_id');
            $table->dropColumn('user_id');
        });


        Schema::table('content_task_groups', function (Blueprint $table) {
            $table->integer('content_id')->unsigned();
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
        });

        Schema::table('content_tasks', function (Blueprint $table) {
            $table->integer('content_task_group_id')->unsigned();
            $table->foreign('content_task_group_id')->references('id')->on('content_task_groups')->onDelete('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_task_groups', function (Blueprint $table) {
            $table->dropForeign('content_task_groups_content_id_foreign');
        });

        Schema::table('content_tasks', function (Blueprint $table) {
            $table->dropForeign('content_tasks_content_task_group_id_foreign');
            $table->dropForeign('content_tasks_user_id_foreign');
        });
    }

}
