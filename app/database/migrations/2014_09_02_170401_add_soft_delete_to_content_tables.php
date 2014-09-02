<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddSoftDeleteToContentTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('content', function(Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('content_activities', function(Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('content_comments', function(Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('content_related', function(Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('content_tags', function(Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('content_tasks', function(Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('content_task_groups', function(Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('launches', function(Blueprint $table){
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('content', function(Blueprint $table){
            $table->dropSoftDeletes();
        });
        Schema::table('content_activities', function(Blueprint $table){
            $table->dropSoftDeletes();
        });
        Schema::table('content_comments', function(Blueprint $table){
            $table->dropSoftDeletes();
        });
        Schema::table('content_related', function(Blueprint $table){
            $table->dropSoftDeletes();
        });
        Schema::table('content_tags', function(Blueprint $table){
            $table->dropSoftDeletes();
        });
        Schema::table('content_tasks', function(Blueprint $table){
            $table->dropSoftDeletes();
        });
        Schema::table('content_task_groups', function(Blueprint $table){
            $table->dropSoftDeletes();
        });
        Schema::table('launches', function(Blueprint $table){
            $table->dropSoftDeletes();
        });
	}

}
