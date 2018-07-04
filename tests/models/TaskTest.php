<?php

use App\Task;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TaskTest extends TestCase
{
    use MailTracking;

    protected $account;

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

    public function testSendsEmailsToAssignedUsers()
    {
        $userA = factory(User::class)->create([ 'name' => 'User A', 'email' => 'userA@test.com' ]);
        $userB = factory(User::class)->create([ 'name' => 'User B', 'email' => 'userB@test.com' ]);
        $userC = factory(User::class)->create([ 'name' => 'User C', 'email' => 'userC@test.com' ]);
        $userD = factory(User::class)->create([ 'name' => 'User D', 'email' => 'userD@test.com' ]);

        $task = factory(Task::class)->create();
        $task->assignedUsers()->attach([ $userA->id, $userB->id ]);

        $this->assertEquals(2, $task->assignedUsers()->count());

        $this->assertTrue($task->hasAssignedUser($userA));
        $this->assertTrue($task->hasAssignedUser($userB));
        $this->assertFalse($task->hasAssignedUser($userC));
        $this->assertFalse($task->hasAssignedUser($userD));

        $task->assignUsers([ $userA->id, $userC->id ]);

        $this->seeEmailsSent(1);
        $this->seeEmailTo($userC->email);

        $this->assertEquals(2, $task->assignedUsers()->count());

        $this->assertTrue($task->hasAssignedUser($userA));
        $this->assertFalse($task->hasAssignedUser($userB));
        $this->assertTrue($task->hasAssignedUser($userC));
        $this->assertFalse($task->hasAssignedUser($userD));
    }

    public function testOpensAndClosesTasks()
    {
        $task = factory(Task::class)->create([ 'status' => 'closed' ]);
        $task->open();

        $this->assertEquals('open', $task->status);

        $task->close();

        $this->assertEquals('closed', $task->status);
    }
}
