<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreviewTitleToWriterAccessOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('writer_access_orders', function (Blueprint $table) {
            $table->string('preview_title')->after('preview_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('writer_access_orders', function (Blueprint $table) {
            $table->dropColumn([ 'preview_title' ]);
        });
    }
}
