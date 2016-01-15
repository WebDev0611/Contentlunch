<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTraackrTagsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traackr_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id')->unsigned()->nullable();
            $table->integer('campaign_id')->unsigned()->nullable();
            $table->integer('account_id')->unsigned();
            $table->text('user');
            $table->string('traackr_id'); // it's a hex string
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
        Schema::drop('traackr_tags');
    }

}
