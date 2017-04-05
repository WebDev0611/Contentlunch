<?php

class PersonaSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        App\Persona::truncate();
        factory(App\Persona::class, 5)->create();

        $this->enableForeignKeys();
    }
}
