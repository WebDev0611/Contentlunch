<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeedbackMessageToLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('limits', function (Blueprint $table) {
            $table->string('feedback_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('limits', function (Blueprint $table) {
            $table->dropColumn([ 'feedback_message' ]);
        });
    }
}
