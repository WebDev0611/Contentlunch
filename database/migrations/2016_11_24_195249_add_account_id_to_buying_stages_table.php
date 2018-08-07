<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountIdToBuyingStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buying_stages', function (Blueprint $table) {
            $table->integer('account_id')
                ->unsigned()
                ->nullable();

            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
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
        Schema::table('buying_stages', function (Blueprint $table) {
            $table->dropForeign('buying_stages_account_id_foreign');
            $table->dropColumn('account_id');
        });
    }
}
