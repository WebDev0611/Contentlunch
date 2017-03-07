<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountImageToAccounts extends Migration
{
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('account_image')->nullable()->after('parent_account_id');
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
            $table->dropColumn([ 'account_image' ]);
        });
    }
}
