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
        Artisan::call('db:seed', [ '--class' => 'AccountTypeSeeder' ]);
        Artisan::call('db:seed', [ '--class' => 'ContentTypeTableSeeder' ]);
        Artisan::call('db:seed', [ '--class' => 'UsersTableSeeder' ]);
        Artisan::call('db:seed', [ '--class' => 'ProviderTableSeeder' ]);
    }
}
