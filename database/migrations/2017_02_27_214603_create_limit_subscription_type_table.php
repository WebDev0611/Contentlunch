<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLimitSubscriptionTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('limit_subscription_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('limit_id')->unsigned();
            $table->integer('subscription_type_id')->unsigned();
            $table->timestamps();

            $table
                ->foreign('limit_id')
                ->references('id')
                ->on('limits')
                ->onDelete('cascade');

            $table
                ->foreign('subscription_type_id')
                ->references('id')
                ->on('subscription_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('limit_subscription_type');
    }
}
