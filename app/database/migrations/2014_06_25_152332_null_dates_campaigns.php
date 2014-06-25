<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullDatesCampaigns extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function ($table) {
            $db = Config::get('database.default');
            echo ' DB TYPE: '. $db .' ';
            if ($db == 'sqlsrv') {
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [start_date] datetime NULL");
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [end_date] datetime NULL");
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [is_active] tinyint NULL");
            } else {
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `start_date` timestamp NULL");
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `end_date` timestamp NULL");
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `is_active` tinyint(1) NULL");
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
        Schema::table('campaigns', function ($table) {
            $db = Config::get('database.default');
            if ($db == 'sqlsrv') {
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [start_date] datetime NOT NULL");
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [end_date] datetime NOT NULL");
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [is_active] tinyint NOT NULL");
            } else {
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `start_date` timestamp NOT NULL");
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `end_date` timestamp NOT NULL");
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `is_active` tinyint(1) NOT NULL");
            }
        });
    }
    
}
