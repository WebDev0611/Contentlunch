<?php

use App\Account;
use App\Content;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AccountTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testGuestList()
    {
        $account = factory(Account::class, 'company')->create();
        $contentA = factory(Content::class)->create([ 'account_id' => $account->id ]);
        $contentB = factory(Content::class)->create([ 'account_id' => $account->id ]);

        $guestA = factory(User::class, 'guest')->create();
        $guestB = factory(User::class, 'guest')->create();
        $guestC = factory(User::class, 'guest')->create();

        $contentA->guests()->sync([ $guestA->id, $guestB->id ]);
        $contentB->guests()->sync([ $guestA->id, $guestC->id ]);

        $guests = $account->guestList();

        $this->assertEquals(3, $guests->count());
        $this->assertEquals(1, $guests->where('id', $guestA->id)->count());
        $this->assertEquals(1, $guests->where('id', $guestB->id)->count());
        $this->assertEquals(1, $guests->where('id', $guestC->id)->count());
    }
}
