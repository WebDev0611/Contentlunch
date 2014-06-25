<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullCampaignTypeId extends Migration {


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
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [campaign_type_id] int NULL");
            } else {
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `campaign_type_id` int(11) NULL");
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
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [campaign_type_id] int NOT NULL");
            } else {
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `campaign_type_id` int(11) NOT NULL");
            }
        });
    }
}
