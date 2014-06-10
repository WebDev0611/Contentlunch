
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampaignAllowNull extends Migration {

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
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [description] text NULL");
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [goals] text NULL");
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [is_recurring] tinyint NULL");
            } else {
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `description` text NULL");
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `goals` text NULL");
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `is_recurring` tinyint(1) NULL");
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
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [description] text NOT NULL");
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [goals] text NOT NULL");
                DB::unprepared("ALTER TABLE [campaigns] ALTER COLUMN [is_recurring] tinyint NOT NULL");
            } else {
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `description` text NOT NULL");
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `goals` text NOT NULL");
                DB::unprepared("ALTER TABLE `campaigns` MODIFY COLUMN `is_recurring` tinyint(1) NOT NULL");
            }
        });
    }

}
