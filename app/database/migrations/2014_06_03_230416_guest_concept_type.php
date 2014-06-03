<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GuestConceptType extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest_collaborators', function (Blueprint $table) {
            $table->enum('content_type', ['content', 'campaign'])->after('type')->default('content');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guest_collaborators', function (Blueprint $table) {
            $table->dropColumn('content_type');
        });
    }

}
