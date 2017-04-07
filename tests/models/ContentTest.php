<?php

use App\ContentStatus;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Content;
use App\Account;

class ContentTest extends TestCase
{
    protected $accounts;

    public function setUp()
    {
        parent::setUp();
    }

    protected function createAccountsAndContents()
    {
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
        $this->createAccountsAndContents();
        $contents = Content::search('Test', $this->accounts[0]);

        $this->assertEquals(1, $contents->count());
    }

    public function testContentSearchesBody()
    {
        $this->createAccountsAndContents();
        $contents = Content::search('body content', $this->accounts[0]);

        $this->assertEquals(1, $contents->count());
    }

    public function testConfigureAction()
    {
        $content = factory(Content::class)->create();

        $content->configureAction('ready_to_publish');
        $this->assertEquals($content->content_status_id, ContentStatus::READY);

        $content->configureAction('publish');
        $this->assertEquals($content->content_status_id, ContentStatus::PUBLISHED);

        $content->configureAction('archived');
        $this->assertEquals($content->content_status_id, ContentStatus::ARCHIVED);

        $content->configureAction();
        $this->assertEquals($content->content_status_id, ContentStatus::BEING_WRITTEN);
    }

    public function testStatusScopes()
    {
        $contentA = factory(Content::class)->create([ 'content_status_id' => ContentStatus::READY ]);
        $contentB = factory(Content::class)->create([ 'content_status_id' => ContentStatus::PUBLISHED ]);
        $contentC = factory(Content::class)->create([ 'content_status_id' => ContentStatus::ARCHIVED ]);
        $contentD = factory(Content::class)->create([ 'content_status_id' => ContentStatus::BEING_WRITTEN ]);

        $this->assertEquals(Content::readyToPublish()->first()->id, $contentA->id);
        $this->assertEquals(Content::published()->first()->id, $contentB->id);
        $this->assertEquals(Content::archived()->first()->id, $contentC->id);
        $this->assertEquals(Content::written()->first()->id, $contentD->id);

        factory(Content::class, 1)->create([ 'content_status_id' => ContentStatus::READY ]);
        factory(Content::class, 2)->create([ 'content_status_id' => ContentStatus::PUBLISHED ]);
        factory(Content::class, 3)->create([ 'content_status_id' => ContentStatus::ARCHIVED ]);
        factory(Content::class, 4)->create([ 'content_status_id' => ContentStatus::BEING_WRITTEN ]);

        $this->assertEquals(Content::readyToPublish()->count(), 2);
        $this->assertEquals(Content::published()->count(), 3);
        $this->assertEquals(Content::archived()->count(), 4);
        $this->assertEquals(Content::written()->count(), 5);
    }
}
