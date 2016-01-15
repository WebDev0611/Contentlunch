<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrainstormTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brainstorms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('guest_id')->nullable();
            $table->integer('content_id')->nullable();
            $table->integer('campaign_id')->nullable();
            $table->integer('account_id');
            $table->text('agenda'); // json array 
            $table->date('date');
            $table->time('time');
            $table->text('description');
            $table->text('credentials');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('brainstorms');
    }

}
