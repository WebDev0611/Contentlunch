<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddsPrimaryKeyAndTimestampsToContentUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_user', function (Blueprint $table) {
            $table->increments('id')->before('content_id');
            $table->timestamps();
        });

        DB::table('content_user')->update([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_user', function (Blueprint $table) {
            $table->dropColumn([ 'id', 'created_at', 'updated_at' ]);
        });
    }
}
