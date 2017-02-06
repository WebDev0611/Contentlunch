<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use DatabaseTransactions;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    protected $seeders = [
        'AccountTypeSeeder',
        'ContentTypeTableSeeder',
        'UsersTableSeeder',
        'ProviderTableSeeder',
    ];

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        $this->runSeeders();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    protected function runSeeders()
    {
        foreach ($this->seeders as $seeder) {
            Artisan::call('db:seed', [ '--class' => $seeder ]);
        }
    }
}
