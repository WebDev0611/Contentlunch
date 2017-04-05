<?php

use App\Account;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TaskControllerTest
 *
 * We need to rework this to mock the database. For now it's best to touch the DB
 * than to have no test.
 */
class TaskControllerTest extends TestCase
{
    protected $account;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->account = factory(Account::class)->create();
        $this->user = factory(User::class)->create([ 'name' => 'Task Tester']);
        $this->user->accounts()->attach($this->account);

    }

    public function testTasksStatusCanBeAlteredByCreator()
    {
        $this->be($this->user);
        $task = factory(Task::class)->create([
            'account_id' => $this->account->id,
            'user_id' => $this->user->id,
            'status' => 'archived',
        ]);

        $this->assertEquals($task->user_id, $this->user->id);

        $response = $this->action('POST', 'TaskController@close', $task);

        $this->assertEquals('closed', Task::find($task->id)->status);
        $this->assertEquals($response->getStatusCode(), 200);

        $response = $this->action('POST', 'TaskController@open', $task);

        $this->assertEquals('open', Task::find($task->id)->status);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testTasksStatusCanBeAlteredByAssignedUsers()
    {
        $this->be($this->user);
        $task = factory(Task::class)->create([
            'account_id' => $this->account->id,
            'status' => 'archived',
        ]);

        $task->assignedUsers()->attach($this->user);
        $this->assertTrue($task->hasAssignedUser($this->user));

        $response = $this->action('POST', 'TaskController@close', $task);

        $this->assertEquals('closed', Task::find($task->id)->status);
        $this->assertEquals($response->getStatusCode(), 200);

        $response = $this->action('POST', 'TaskController@open', $task);

        $this->assertEquals('open', Task::find($task->id)->status);
        $this->assertEquals($response->getStatusCode(), 200);
    }
}
