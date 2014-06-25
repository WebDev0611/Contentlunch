<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGuestToComments extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_comments', function (Blueprint $table) {
            $table->integer('guest_id')->unsigned()->nullable()->after('user_id');
            
            $db = Config::get('database.default');
            echo ' DB TYPE: '. $db .' ';
            if ($db == 'sqlsrv') {
                DB::unprepared("ALTER TABLE [content_comments] ALTER COLUMN [user_id] int NULL");
            } else {
                DB::unprepared("ALTER TABLE `content_comments` MODIFY COLUMN `user_id` int(11) NULL");
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_comments', function (Blueprint $table) {
            $table->dropColumn('guest_id');

            $db = Config::get('database.default');
            if ($db == 'sqlsrv') {
                DB::unprepared("ALTER TABLE [content_comments] ALTER COLUMN [user_id] int NOT NULL");
            } else {
                DB::unprepared("ALTER TABLE `content_comments` MODIFY COLUMN `user_id` int(11) NOT NULL");
            }
        });
    }

}
