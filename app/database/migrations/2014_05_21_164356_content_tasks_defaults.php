<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentTasksDefaults extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_task_groups', function ($table) {
            $table->timestamp('date_completed')->nullable();
            $table->boolean('is_complete')->default(false);
        });

        Schema::table('content_tasks', function ($table) {
            $table->dropColumn('is_complete');
            $table->timestamp('date_completed')->nullable();
        });

        Schema::table('content_tasks', function ($table) {
            $table->boolean('is_complete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_task_groups', function ($table) {
            $table->dropColumn('date_completed', 'is_complete');
        });

        Schema::table('content_tasks', function ($table) {
            $table->dropColumn('date_completed', 'is_complete');
        });

        Schema::table('content_tasks', function ($table) {
            $table->boolean('is_complete')->default(null);
        });
    }

}
