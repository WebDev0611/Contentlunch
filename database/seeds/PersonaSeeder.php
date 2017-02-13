<?php

use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        App\Persona::truncate();
        factory(App\Persona::class, 5)->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
