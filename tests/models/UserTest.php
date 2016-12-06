<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Account;

class UserTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
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
        $this->assertEquals(1, User::search('Teddy', $this->account)->count());
        $this->assertEquals(1, User::search('Dolores', $this->account)->count());
    }
}
