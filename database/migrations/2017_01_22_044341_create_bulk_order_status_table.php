<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkOrderStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writer_access_bulk_order_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status_percentage');
            $table->integer('total_orders');
            $table->integer('completed_orders');
            $table->boolean('completed', false);
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
        Schema::drop('writer_access_bulk_order_statuses');
    }
}
