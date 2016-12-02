<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Connection;
use App\User;

class AddAccountIdToConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->integer('account_id')
                ->after('user_id')
                ->unsigned()
                ->nullable();
        });

        foreach (Connection::all() as $connection) {
            $user = User::find($connection->user_id);
            $account = $user->accounts->first();

            if ($account) {
                $connection->account_id = $account->id;
                $connection->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->dropColumn('account_id');
        });
    }
}
