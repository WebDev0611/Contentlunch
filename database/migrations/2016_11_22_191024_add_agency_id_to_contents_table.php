<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Content;
use App\User;

class AddAgencyIdToContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->integer('account_id')
                ->after('content_type_id')
                ->unsigned()
                ->nullable();
        });

        foreach (Content::all() as $content) {
            $user = User::find($content->user_id);
            $account = $user->accounts->first();

            if ($account) {
                $content->account_id = $account->id;
                $content->save();
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
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn('account_id');
        });
    }
}
