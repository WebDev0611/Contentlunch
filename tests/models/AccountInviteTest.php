<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use App\Account;
use App\User;
use App\AccountInvite;

class AccountInviteTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->invitedUser = factory(User::class)->create([ 'email' => 'test@email.com' ]);
        $this->account = factory(Account::class)->create();

        $this->account->users()->attach($this->invitedUser);
    }

    public function testCreatesInviteToken()
    {
        $invite = AccountInvite::create([
            'email' => 'new@email.com',
            'account_id' => $this->account->id,
        ]);

        $this->assertEquals($this->account->id, $invite->account_id);
        $this->assertInternalType('string', $invite->token, "Got a " . gettype($invite->token) . " instead of a string");
        $this->assertNotEmpty($invite->token);
    }

    public function testIsUsedWorks()
    {
        $usedInvite = AccountInvite::create([
            'email' => 'new@email.com',
            'account_id' => $this->account->id,
        ]);

        $usedInvite->user()->associate(factory(User::class)->create());

        $unusedInvite = AccountInvite::create([
            'email' => 'another@email.com',
            'account_id' => $this->account->id,
        ]);

        $this->assertTrue($usedInvite->isUsed());
        $this->assertFalse($unusedInvite->isUsed());
    }
}
