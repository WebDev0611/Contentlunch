<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWriterAccessCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writer_access_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->string('timestamp');
            $table->integer('writer_id')->unsigned()->nullable();
            $table->string('writer_name')->nullable();
            $table->integer('editor_id')->unsigned()->nullable();
            $table->string('editor_name')->nullable();
            $table->boolean('from_client')->default(false);
            $table->text('note')->default('');
            $table->boolean('client_notified')->default(false);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::drop('writer_access_comments');
    }
}
