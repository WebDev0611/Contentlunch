<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPromoDiscountFieldToWriterAccessPartialOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('writer_access_partial_orders', function (Blueprint $table) {
            $table->float('promo_discount')->default(0);
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
            $table->dropColumn('promo_discount');
        });
    }
}
