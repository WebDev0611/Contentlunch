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
        $this->account = factory(App\Account::class)->create();
        $this->content = factory(App\Content::class)->create([
            'title' => 'Test Title',
            'body' => '<p>test body content</p>',
            'account_id' => $this->account->id,
        ]);

        factory(App\Content::class)->create([
            'title' => 'Another Title',
            'body' => '<p>another body</p>',
            'account_id' => $this->account->id,
        ]);

        factory(App\Content::class)->create([
            'title' => 'Another Title 2',
            'body' => '<p>another body 2</p>',
            'account_id' => $this->account->id,
        ]);
    }

    public function testContentSearchesTitle()
    {
        $contents = Content::search('Test', $this->account);

        $this->assertEquals(1, $contents->count());
    }

    public function testContentSearchesBody()
    {
        $contents = Content::search('body content', $this->account);

        $this->assertEquals(1, $contents->count());
    }
}
