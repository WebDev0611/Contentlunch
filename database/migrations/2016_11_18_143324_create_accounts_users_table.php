<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\User;

class CreateAccountsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table
                ->foreign('account_id', 'account_id_fk')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');

            $table
                ->foreign('user_id', 'user_id_fk')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unique([ 'account_id', 'user_id' ]);
        });

        $accountsUsers = User::all()
            ->map(function($user) {
                return [
                    'account_id' => $user->account_id,
                    'user_id' => $user->id,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ];
            })
            ->toArray();

        DB::table('account_user')->insert($accountsUsers);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('account_user');
    }
}
