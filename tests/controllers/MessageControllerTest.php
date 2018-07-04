<?php

use App\Account;
use App\Message;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class MessageControllerTest extends TestCase
{
    protected $account;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->account = factory(Account::class)->create();
        $this->user = factory(User::class)->create([ 'name' => 'Message Tester']);
        $this->user->accounts()->attach($this->account);
    }

    public function testMessagesAreMarkedAsRead()
    {
        $this->be($this->user);

        $userB = factory(User::class)->create();
        $userB->accounts()->attach($this->account);

        factory(Message::class, 10)->create([
            'sender_id' => $userB->id,
            'recipient_id' => $this->user->id,
            'read' => false,
        ]);

        factory(Message::class, 10)->create([
            'sender_id' => $this->user->id,
            'recipient_id' => $userB->id,
            'read' => false,
        ]);

        $this->json('post', route('messages.mark_as_read', $userB))->assertResponseStatus(200);

        $readMessages = Message::where('sender_id', $userB->id)
            ->where('recipient_id', $this->user->id)
            ->where('read', true)
            ->get();

        $unreadMessages = Message::where('sender_id', $this->user->id)
            ->where('recipient_id', $userB->id)
            ->where('read', false)
            ->get();

        $this->assertEquals(10, $readMessages->count());
        $this->assertEquals(10, $unreadMessages->count());
    }
}
