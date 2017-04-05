<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderCountToWriterAccessPartialOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('writer_access_partial_orders', function (Blueprint $table) {
            $table->string('order_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('writer_access_partial_orders', function (Blueprint $table) {
            $table->dropColumn('order_count');
        });
    }
}
