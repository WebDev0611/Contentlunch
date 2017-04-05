<?php

use Illuminate\Database\Seeder;

abstract class BaseSeeder extends Seeder
{
    protected function disableForeignKeys()
    {
        if (getenv('DB_CONNECTION') !== 'sqlite_memory') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
    }

    protected function enableForeignKeys()
    {
        if (getenv('DB_CONNECTION') !== 'sqlite_memory') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}