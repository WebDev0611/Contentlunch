<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveProjectNameFromWriterAccessPartialOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('writer_access_partial_orders', function (Blueprint $table) {
            $table->dropColumn('project_name');
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
            $table->string('project_name')->nullable();
        });
    }
}
