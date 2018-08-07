<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\User;

class RemoveAccountIdFromUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_account_id');
            $table->dropColumn('account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('account_id')
                ->unsigned()
                ->nullable();

            $table->foreign('account_id', 'fk_account_id')
                ->references('id')
                ->on('accounts');
        });

        $accountUsers = DB::table('account_user')->get();

        foreach ($accountUsers as $accountUser) {
            User::where('id', $accountUser->user_id)
                ->update([ 'account_id' => $accountUser->account_id ]);
        }
    }
}
