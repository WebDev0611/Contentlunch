<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

// Eh... google api lib not namespaced
use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Blogger;
use Google_Service_Blogger_Post;
use Google_Http_Batch;

class BloggerAPI extends GoogleAPI
{

    protected $configKey = 'services.blogger';

    public function getIdentifier()
    {
        $me = $this->getMe();
        $name = ucwords($me['user']->name);
        // Todo, user should be able to specify which blog to post to?
        if (!empty($me['blogs']->items[0]['name'])) {
            $name .= ' (' . $me['blogs']->items[0]['name'] . ')';
        }
        return $name;
    }

    public function getMe()
    {
        if (!$this->me) {
            $client = $this->getClient();
            $api = new Google_Service_Oauth2($client);
            $userInfo = $api->userinfo->get();
            $api = new Google_Service_Blogger($client);
            $blogs = $api->blogs->listByUser('self');
            $this->me = [
                'user' => $userInfo,
                'blogs' => $blogs
            ];
        }
        return $this->me;
    }

    public function getUrl()
    {
        $me = $this->getMe();
        if (!empty($me['blogs']->items[0]['url'])) {
            return $me['blogs']->items[0]['url'];
        }
        return self::NA_TEXT;
    }

    public function updateStats($accountConnectionId)
    {

        $temp = \AccountConnection::find($accountConnectionId)
            ->content()
            ->withPivot('external_id', 'likes', 'shares')
            ->get();

        $content = array();
        foreach ($temp as $c) {
            $content[$c->pivot->external_id] = $c;
        }

        //var_dump($content);

        $client = $this->getClient();

        $info = $this->getMe();
        $api = new Google_Service_Blogger($client);
        if (empty($info['blogs']->items[0]['id'])) {
            throw new \Exception("No blog found, setup blog on blogger");
        }
        $blog_id = $info['blogs']->items[0]['id'];


        $client->setUseBatch(true);
        $batch = new Google_Http_Batch($client);

        foreach($content as $id => $c) {
            $req = $api->posts->get($blog_id, $id);
            $batch->add($req, $id);
        }

        $results = $batch->execute();

        $count = 0;
        foreach ($content as $id => $c) {
            if (isset($results['response-'.$id])) {
                $count++;

                $c->pivot->likes = $results['response-'.$id]->getReplies()->totalItems;
                $c->pivot->save();
            }
        }

        return json_encode(['success' => 1, 'count' => $count]);
    }

    /**
     * @see https://developers.google.com/blogger/docs/3.0/reference/posts#resource
     */
    public function postContent($content)
    {
        $response = ['success' => true, 'response' => []];
        try {
            $client = $this->getClient();

            $info = $this->getMe();
            $api = new Google_Service_Blogger($client);
            if (empty($info['blogs']->items[0]['id'])) {
                throw new \Exception("No blog found, setup blog on blogger");
            }
            $post = new Google_Service_Blogger_Post;
            $body = $content->body;
            // Quick and dirty way of inserting featured image into the post...
            // doesn't give the user much options tho
            $upload = $content->upload()->first();
            if ($upload && $upload->media_type == 'image') {
                $body = '<img src="' . $upload->getUrl() . '" style="float: left; margin: 0 10px 10px 0" />' . $body;
            }
            $post->setContent($body);
            $post->setTitle($content->title);

            $apiResponse = $api->posts->insert($info['blogs']->items[0]['id'], $post);
            $response['success'] = true;
            $response['response'] = $apiResponse;
            $response['external_id'] = $apiResponse->getId();
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['response'] = $apiResponse;
            $response['error'] = $e->getMessage();
        }
        return $response;
    }

}