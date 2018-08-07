<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeContentIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropColumn('content_id');
        });

        Schema::table('attachments', function (Blueprint $table) {
            $table->integer('content_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropColumn('content_id');
        });

        Schema::table('attachments', function (Blueprint $table) {
            $table->integer('content_id')->unsigned()->nullable();
        });
    }
}
