<?php

use App\Account;
use App\Message;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    protected $account;
    protected $userTeddy;
    protected $userDolores;

    public function setUp()
    {
        parent::setUp();
    }

    protected function createUsers()
    {
        $this->account = factory(App\Account::class)->create();

        $this->userTeddy = factory(App\User::class)->create([ 'name' => 'Teddy Flood' ]);
        $this->userDolores = factory(App\User::class)->create([ 'name' => 'Dolores Abernathy' ]);

        $this->userTeddy->accounts()->attach($this->account);
        $this->userDolores->accounts()->attach($this->account);

        factory(App\User::class)->create([ 'name' => 'Teddy Weber' ]);
        factory(App\User::class)->create([ 'name' => 'Dolores Weber' ]);
    }

    public function testSearchesName()
    {
        $this->createUsers();
        $this->assertEquals(1, User::search('Teddy', $this->account)->count());
        $this->assertEquals(1, User::search('Dolores', $this->account)->count());
    }

    public function testBelongsToAgencyAccount()
    {
        list($userA, $userB) = factory(User::class, 2)->create();
        $agency = factory(Account::class, 'agency')->create();
        $company = factory(Account::class, 'company')->create();

        $userA->accounts()->attach($agency);
        $userB->accounts()->attach($company);

        $this->assertTrue($userA->belongsToAgencyAccount());
        $this->assertFalse($userB->belongsToAgencyAccount());

        $this->assertEquals($agency->id, $userA->agencyAccount()->id);
    }

    public function testMessagesAreReturnedCorrectly()
    {
        list($userA, $userB) = factory(User::class, 2)->create();

        factory(Message::class)->create(['sender_id' => $userA->id, 'recipient_id' => $userB->id, 'created_at' => Carbon::now()->subDays(1)]);
        factory(Message::class)->create(['sender_id' => $userB->id, 'recipient_id' => $userA->id, 'created_at' => Carbon::now()]);

        $conversation = $userA->conversationWith($userB);

        $this->assertEquals(2, $conversation->count());
        $this->assertEquals($userA->id, $conversation[1]->sender_id);
        $this->assertEquals($userB->id, $conversation[1]->recipient_id);
        $this->assertEquals($userB->id, $conversation[0]->sender_id);
        $this->assertEquals($userA->id, $conversation[0]->recipient_id);
    }
}
