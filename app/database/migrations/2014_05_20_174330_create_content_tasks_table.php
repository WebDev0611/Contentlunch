<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTasksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_task_groups', function ($table) {
            $table->increments('id');
            $table->integer('status'); // 1 = create
            $table->date('due_date');
            $table->integer('content_id');
            $table->timestamps();
        });

        Schema::create('content_tasks', function ($table) {
            $table->increments('id');
            $table->boolean('is_complete');
            $table->date('due_date');
            $table->string('name');
            $table->integer('user_id');
            $table->integer('content_task_group_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content_task_groups');
        Schema::drop('content_tasks');
    }

}
