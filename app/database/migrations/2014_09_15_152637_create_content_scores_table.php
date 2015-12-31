<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('content', function(Blueprint $table){
            $table->dropColumn('score');
        });

        Schema::create('content_scores', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id')->unsigned();
            $table->integer('campaign_id')->unsigned()->nullable();
            $table->date('date');
            $table->float('offsiteScore')->nullable();
            $table->float('onsiteScore')->nullable();
            $table->float('score')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));

            $table->unique(['content_id', 'date']);
            $table->foreign('content_id')
                ->references('id')->on('content')
                ->onDelete('cascade');
            $table->foreign('campaign_id')
                ->references('id')->on('campaigns')
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
        Schema::drop('content_scores');

        Schema::table('content', function(Blueprint $table){
            $table->float('score')->nullable()->default(null);
        });
	}

}
