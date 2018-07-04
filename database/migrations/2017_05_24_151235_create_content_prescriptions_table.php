<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_prescriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_type');
            $table->string('goal');
            $table->float('budget');
            $table->string('content_package');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content_prescriptions');
    }
}
