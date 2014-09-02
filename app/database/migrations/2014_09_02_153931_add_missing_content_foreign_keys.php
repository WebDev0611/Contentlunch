<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingContentForeignKeys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        $tables = [
            'launches', 'content_account_connections', 'content_activities', 'content_collaborators', 'content_comments',
            'content_related', 'content_tags', 'content_task_groups', 'content_uploads'
        ];
        foreach ($tables as $table) {
            DB::statement('delete from '. $table .' where not exists (select 1 from content where content.id = content_id)');
        }
        DB::statement('ALTER TABLE `content_account_connections`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) UNSIGNED NOT NULL AFTER `id`;');
        Schema::table('content_account_connections', function(Blueprint $table){
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE `content_activities`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) UNSIGNED NOT NULL AFTER `id`;');
        Schema::table('content_activities', function(Blueprint $table){
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE `content_collaborators`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) UNSIGNED NOT NULL AFTER `id`;');
        Schema::table('content_collaborators', function(Blueprint $table){
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE `content_comments`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) UNSIGNED NOT NULL AFTER `id`;');
        Schema::table('content_comments', function(Blueprint $table){
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE `content_related`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) UNSIGNED NOT NULL AFTER `id`;');
        Schema::table('content_related', function(Blueprint $table){
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE `content_tags`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) UNSIGNED NOT NULL AFTER `id`;');
        Schema::table('content_tags', function(Blueprint $table){
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE `content_uploads`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) UNSIGNED NOT NULL AFTER `id`;');
        Schema::table('content_uploads', function(Blueprint $table){
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('content_account_connections', function(Blueprint $table){
            $table->dropForeign('content_account_connections_content_id_foreign');
        });
        DB::statement('ALTER TABLE `content_account_connections`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) NOT NULL AFTER `id`;');

        Schema::table('content_activities', function(Blueprint $table){
            $table->dropForeign('content_activities_content_id_foreign');
        });
        DB::statement('ALTER TABLE `content_activities`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) NOT NULL AFTER `id`;');

        Schema::table('content_collaborators', function(Blueprint $table){
            $table->dropForeign('content_collaborators_content_id_foreign');
        });
        DB::statement('ALTER TABLE `content_collaborators`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) NOT NULL AFTER `id`;');

        Schema::table('content_comments', function(Blueprint $table){
            $table->dropForeign('content_comments_content_id_foreign');
        });
        DB::statement('ALTER TABLE `content_comments`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) NOT NULL AFTER `id`;');

        Schema::table('content_related', function(Blueprint $table){
            $table->dropForeign('content_related_content_id_foreign');
        });
        DB::statement('ALTER TABLE `content_related`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) NOT NULL AFTER `id`;');

        Schema::table('content_tags', function(Blueprint $table){
            $table->dropForeign('content_tags_content_id_foreign');
        });
        DB::statement('ALTER TABLE `content_tags`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) NOT NULL AFTER `id`;');

        Schema::table('content_uploads', function(Blueprint $table){
            $table->dropForeign('content_uploads_content_id_foreign');
        });
        DB::statement('ALTER TABLE `content_uploads`
	                    CHANGE COLUMN `content_id` `content_id` INT(11) NOT NULL AFTER `id`;');
	}

}
