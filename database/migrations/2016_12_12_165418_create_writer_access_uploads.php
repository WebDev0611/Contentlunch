<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWriterAccessUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writer_access_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('writer_access_partial_order_id')->unsigned();
            $table->string('file_path')->default('');
            $table->timestamps();

            $table->foreign('writer_access_partial_order_id')
                ->references('id')
                ->on('writer_access_partial_orders')
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
        Schema::drop('writer_access_uploads');
    }
}
