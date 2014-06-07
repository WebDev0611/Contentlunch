<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtraCampaignFields extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('contact')->nullable();
            $table->string('partners')->nullable();
            $table->string('speaker_name')->nullable();
            $table->string('host')->nullable();
            $table->string('type')->nullable();
            $table->string('audio_link')->nullable();
            $table->boolean('photo_needed')->nullable();
            $table->boolean('link_needed')->nullable();
            $table->boolean('is_series')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('contact');
            $table->dropColumn('partners');
            $table->dropColumn('speaker_name');
            $table->dropColumn('host');
            $table->dropColumn('type');
            $table->dropColumn('audio_link');
            $table->dropColumn('photo_needed');
            $table->dropColumn('link_needed');
            $table->dropColumn('is_series');
        });
    }

}
