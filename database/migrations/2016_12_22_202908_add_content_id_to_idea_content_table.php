<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContentIdToIdeaContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('idea_content', function (Blueprint $table) {
            $table->integer('content_id')->unsigned()->after('idea_id');

            $table->foreign('content_id')
                ->references('id')
                ->on('contents')
                ->onDelete('cascade');

            $table->foreign('idea_id')
                ->references('id')
                ->on('idea')
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
        Schema::table('idea_content', function (Blueprint $table) {
            $table->dropForeign('idea_content_content_id_foreign');
            $table->dropForeign('idea_content_idea_id_foreign');
            $table->dropColumn('content_id');
        });
    }
}
