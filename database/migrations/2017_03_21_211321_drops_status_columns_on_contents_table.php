<?php

use App\Content;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropsStatusColumnsOnContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn([ 'archived', 'ready_published', 'published', 'written']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->boolean('archived')->default(0);
            $table->boolean('ready_published')->default(0);
            $table->boolean('published')->default(0);
            $table->boolean('written')->default(0);
        });

        Content::all()->each(function($content) {
            switch ($content->content_status_id) {
                case 1: $content->written = 1; break;
                case 2: $content->ready_published = 1; break;
                case 3: $content->published = 1; break;
                case 4: $content->archived = 1; break;
                default: break;
            }

            $content->save();
        });
    }
}
