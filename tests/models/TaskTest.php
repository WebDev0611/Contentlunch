<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Task;

class TaskTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->account = factory(App\Account::class)->create();

        factory(Task::class)->create([
            'name' => 'New Narrative',
            'explanation' => 'This is the new narrative.',
            'account_id' => $this->account->id
        ]);

        factory(Task::class)->create([
            'name' => 'Gran Finale',
            'explanation' => 'This is the gran finale.',
            'account_id' => $this->account->id
        ]);

        factory(Task::class)->create([
            'name' => 'New Narrative',
            'explanation' => 'This is the new narrative.',
            'account_id' => factory(App\Account::class)->create()->id
        ]);

        factory(Task::class)->create([
            'name' => 'Gran Finale',
            'explanation' => 'This is the gran finale.',
            'account_id' => factory(App\Account::class)->create()->id
        ]);
    }

    public function testSearchesTitle()
    {
        $this->assertEquals(1, Task::search('new narrative', $this->account)->count());
        $this->assertEquals(1, Task::search('gran finale', $this->account)->count());
    }

    public function testSearchesBody()
    {
        $this->assertEquals(1, Task::search('this is the new narrative', $this->account)->count());
        $this->assertEquals(1, Task::search('this is the gran finale', $this->account)->count());
    }
}
