<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAccountsTableToAddAgencyInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->integer('account_type_id')->after('name')->unsigned()->default('1');
            $table->integer('parent_account_id')->after('account_type_id')->unsigned()->nullable();

            $table->foreign('account_type_id', 'account_type_fk')->references('id')->on('account_types');
            $table->foreign('parent_account_id', 'parent_account_fk')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign('account_type_fk');
            $table->dropForeign('parent_account_fk');
            $table->dropColumn(['account_type_id', 'parent_account_id']);
        });
    }
}
