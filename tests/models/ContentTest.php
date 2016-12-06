<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Content;
use App\Account;

class ContentTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->accounts = [
            factory(App\Account::class)->create(),
            factory(App\Account::class)->create(),
        ];

        // Creating three pieces of content for each account.
        factory(App\Content::class)->create([
            'title' => 'Test Title',
            'body' => '<p>test body content</p>',
            'account_id' => $this->accounts[0]->id,
        ]);

        factory(App\Content::class)->create([
            'title' => 'Another Title',
            'body' => '<p>another body</p>',
            'account_id' => $this->accounts[0]->id,
        ]);

        factory(App\Content::class)->create([
            'title' => 'Another Title 2',
            'body' => '<p>another body 2</p>',
            'account_id' => $this->accounts[0]->id,
        ]);

        factory(App\Content::class)->create([
            'title' => 'Test Title From Another Account',
            'body' => '<p>test body content from another account</p>',
            'account_id' => $this->accounts[1]->id,
        ]);

        factory(App\Content::class)->create([
            'title' => 'Another Title From Another Account',
            'body' => '<p>another body from another account</p>',
            'account_id' => $this->accounts[1]->id,
        ]);

        factory(App\Content::class)->create([
            'title' => 'Another Title 2 From Another Account',
            'body' => '<p>another body 2 from another account</p>',
            'account_id' => $this->accounts[1]->id,
        ]);
    }

    public function testContentSearchesTitle()
    {
        $contents = Content::search('Test', $this->accounts[0]);

        $this->assertEquals(1, $contents->count());
    }

    public function testContentSearchesBody()
    {
        $contents = Content::search('body content', $this->accounts[0]);

        $this->assertEquals(1, $contents->count());
    }
}
