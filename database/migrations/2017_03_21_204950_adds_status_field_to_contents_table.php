<?php

use App\Content;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsStatusFieldToContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->integer('content_status_id')->unsigned()->after('meta_description')->default(1);
        });

        Content::all()->each(function($content) {
            $content->content_status_id = 1;

            if ($content->ready_published) {
                $content->content_status_id = 2;
            }

            if ($content->published) {
                $content->content_status_id = 3;
            }

            if ($content->archived) {
                $content->content_status_id = 4;
            }

            $content->save();
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
            $table->dropColumn('content_status_id');
        });


    }
}
