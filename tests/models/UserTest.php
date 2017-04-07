<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Account;

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
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $agency = factory(Account::class, 'agency')->create();
        $company = factory(Account::class, 'company')->create();

        $userA->accounts()->attach($agency);
        $userB->accounts()->attach($company);

        $this->assertTrue($userA->belongsToAgencyAccount());
        $this->assertFalse($userB->belongsToAgencyAccount());

        $this->assertEquals($agency->id, $userA->agencyAccount()->id);
    }
}
