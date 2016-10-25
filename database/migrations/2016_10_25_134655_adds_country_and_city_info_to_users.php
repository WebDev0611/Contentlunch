<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsCountryAndCityInfoToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->char('country_code', 2)->nullable()->index('idx_country_code');
            $table->string('country_name', 45)->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('city')->nullable();
            $table->char('country_code', 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('country_code');
            $table->dropColumn('city');
        });

        Schema::drop('countries');
    }
}
