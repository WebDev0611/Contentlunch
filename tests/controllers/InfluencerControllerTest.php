<?php

use App\Account;
use App\Influencer;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class InfluencerControllerTest extends TestCase
{
    protected $account;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->account = factory(Account::class)->create();
        $this->user = factory(User::class)->create([ 'name' => 'Influencer Tester']);
        $this->user->accounts()->attach($this->account);
    }

    public function testInfluencersCanBeBookmarked()
    {
        $this->be($this->user);

        $request = $this->toggleBookmarkTaylor()
            ->assertResponseStatus(201)
            ->seeJson([ 'twitter_followers_count' => 47639 ])
            ->seeJson([ "twitter_id_str" => "28870687" ])
            ->seeJson([ "twitter_screen_name" => "taylorotwell" ])
            ->seeJson([ "name" => "Taylor Otwell" ])
            ->seeJson([ "description" => "Web Developer. Created Laravel, Forge, Envoyer, and more." ])
            ->seeJson([ "image_url" => "http://pbs.twimg.com/profile_images/745781059040645120/Hg8W0kXe_normal.jpg" ])
            ->seeJsonStructure([
                'data' => [
                    'twitter_followers_count',
                    'twitter_id_str',
                    'twitter_screen_name',
                    'name',
                    'description',
                    'image_url',
                ]
            ]);

        $influencer = $request->response->getData()->data;
        $influencerDB = $this->account->influencers()->find($influencer->id);

        $this->assertEquals(1, $this->account->influencers()->count());
        $this->assertEquals($influencer->twitter_followers_count, $influencerDB->twitter_followers_count);
        $this->assertEquals($influencer->twitter_id_str, $influencerDB->twitter_id_str);
        $this->assertEquals($influencer->twitter_screen_name, $influencerDB->twitter_screen_name);
        $this->assertEquals($influencer->name, $influencerDB->name);
        $this->assertEquals($influencer->description, $influencerDB->description);
        $this->assertEquals($influencer->image_url, $influencerDB->image_url);
    }

    protected function toggleBookmarkTaylor()
    {
        $data = [
            'twitter_followers_count' => 47639,
            'twitter_id_str' => '28870687',
            'id' => 'http://twitter.com/taylorotwell',
            'twitter_listed_count' => 1381,
            'twitter_screen_name_cs' => 'taylorotwell',
            'twitter_screen_name' => 'taylorotwell',
            'name' => 'Taylor Otwell',
            'description' => 'Web Developer. Created Laravel, Forge, Envoyer, and more.',
            'image_url' => 'http://pbs.twimg.com/profile_images/745781059040645120/Hg8W0kXe_normal.jpg',
        ];

        return $this->json('POST', route('influencers.toggle_bookmark'), $data);
    }

    public function testInfluencersCanBeRemoved()
    {
        $this->be($this->user);
        $request = $this->toggleBookmarkTaylor();
        $response = $request->response->getData()->data;

        $influencer = $this->account->influencers()->find($response->id);

        $this->toggleBookmarkTaylor()
            ->seeJson([ 'data' => 'Influencer removed.' ])
            ->assertResponseStatus(200);

        $this->assertEquals(0, $this->account->influencers()->count());
    }

    public function testInfluencersCanBeToggled()
    {
        $this->be($this->user);
        $this->toggleBookmarkTaylor();
        $this->toggleBookmarkTaylor();

        $request = $this->toggleBookmarkTaylor()
            ->assertResponseStatus(201)
            ->seeJson([ 'twitter_followers_count' => 47639 ])
            ->seeJson([ "twitter_id_str" => "28870687" ])
            ->seeJson([ "twitter_screen_name" => "taylorotwell" ])
            ->seeJson([ "name" => "Taylor Otwell" ])
            ->seeJson([ "description" => "Web Developer. Created Laravel, Forge, Envoyer, and more." ])
            ->seeJson([ "image_url" => "http://pbs.twimg.com/profile_images/745781059040645120/Hg8W0kXe_normal.jpg" ])
            ->seeJsonStructure([
                'data' => [
                    'twitter_followers_count',
                    'twitter_id_str',
                    'twitter_screen_name',
                    'name',
                    'description',
                    'image_url',
                ]
            ]);

        $influencer = $request->response->getData()->data;
        $influencerDB = $this->account->influencers()->find($influencer->id);

        $this->assertEquals(1, $this->account->influencers()->count());
        $this->assertEquals(1, Influencer::count());
        $this->assertEquals($influencer->twitter_followers_count, $influencerDB->twitter_followers_count);
        $this->assertEquals($influencer->twitter_id_str, $influencerDB->twitter_id_str);
        $this->assertEquals($influencer->twitter_screen_name, $influencerDB->twitter_screen_name);
        $this->assertEquals($influencer->name, $influencerDB->name);
        $this->assertEquals($influencer->description, $influencerDB->description);
        $this->assertEquals($influencer->image_url, $influencerDB->image_url);
    }
}
