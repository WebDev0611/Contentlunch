<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Idea;

class IdeaTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->account = factory(App\Account::class)->create();

        factory(Idea::class)->create([
            'name' => 'New Narrative',
            'text' => 'This is the new narrative.',
            'account_id' => $this->account->id
        ]);

        factory(Idea::class)->create([
            'name' => 'Gran Finale',
            'text' => 'This is the gran finale.',
            'account_id' => $this->account->id
        ]);

        factory(Idea::class)->create([
            'name' => 'New Narrative',
            'text' => 'This is the new narrative.',
            'account_id' => factory(App\Account::class)->create()->id
        ]);

        factory(Idea::class)->create([
            'name' => 'Gran Finale',
            'text' => 'This is the gran finale.',
            'account_id' => factory(App\Account::class)->create()->id
        ]);
    }

    public function testSearchesTitle()
    {
        $this->assertEquals(1, Idea::search('new narrative', $this->account)->count());
        $this->assertEquals(1, Idea::search('gran finale', $this->account)->count());
    }

    public function testSearchesBody()
    {
        $this->assertEquals(1, Idea::search('this is the new narrative', $this->account)->count());
        $this->assertEquals(1, Idea::search('this is the gran finale', $this->account)->count());
    }
}
