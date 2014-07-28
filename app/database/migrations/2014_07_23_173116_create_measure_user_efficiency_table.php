<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeasureUserEfficiencyTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measure_user_efficiency', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->unique();
            $table->integer('account_id')->unsigned();
            $table->decimal('converted_concepts', 4, 3)->nullable();
            $table->decimal('completed_content_tasks', 4, 3)->nullable();
            $table->decimal('completed_campaign_tasks', 4, 3)->nullable();
            $table->decimal('early_content_tasks', 4, 3)->nullable();
            $table->decimal('early_campaign_tasks', 4, 3)->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('measure_user_efficiency');
    }

}
