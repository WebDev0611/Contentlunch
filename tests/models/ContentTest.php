<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Content;

class ContentTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->account = factory(App\Account::class)->create();
        $this->content = factory(App\Content::class)->create([
            'title' => 'Test Title',
            'body' => '<p>test body content</p>',
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
