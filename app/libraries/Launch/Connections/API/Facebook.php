<?php namespace Launch\Connections\API;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Illuminate\Support\Facades\Config;

/**
 * @see: https://developers.facebook.com/docs/graph-api/reference/v2.0/user/feed
 */
class FacebookAPI extends AbstractConnection
{

    protected $configKey = 'services.facebook';

    protected function getClient()
    {
        if (!$this->client) {
            FacebookSession::setDefaultApplication($this->config['key'], $this->config['secret']);
            $this->client = new FacebookSession($this->getAccessToken());
        }
        return $this->client;
    }

    public function getIdentifier()
    {
        $me = $this->getMe();
        return $me->getName();
    }

    public function getMe()
    {
        if (!$this->me) {
            $session = $this->getClient();
            $this->me = (new FacebookRequest(
                $session, 'GET', '/me'
            ))->execute()->getGraphObject(GraphUser::className());
        }
        return $this->me;
    }

    /**
     * Get the external user / account id
     */
    public function getExternalId()
    {
        $me = $this->getMe();
        return $me->getId();
    }

    public function getUrl()
    {
        $me = $this->getMe();
        return $me->getProperty('link');
    }

    /**
     * Post content to the facebook connection as a status message
     * Strip html tags
     *
     * @todo: Support for pages, groups
     * @todo: Add attachments?
     * @todo: Return response
     */
    public function postContent($content)
    {
        // No html allowed here
        $message = strip_tags($content->body);
        $session = $this->getClient();
        $response = ['success' => true, 'response' => []];
        try {
            // /me translates to user_id of the person or page_id of the page
            // that the access token is mapped to
            $params = [
                'message' => $message
            ];
            $upload = $content->upload()->first();
            if ($upload && $upload->media_type == 'image') {
                $params['picture'] = $upload->getUrl();
            }
            $post = (new FacebookRequest($session, 'POST', '/me/feed', $params))
                ->execute()
                ->getGraphObject(GraphUser::className());
            $response['response'] = $post->asArray();
            $response['external_id'] = $post->getProperty('id');
        } catch (FacebookRequestException $e) {
            $response['success'] = false;
            $response['response'] = $e->getResponse();
            $response['error'] = $e->getMessage();
        } catch (\Exception $e) {
            // Error
            $response['success'] = false;
            $response['response'] = [];
            $response['error'] = $e->getMessage();
        }
        return $response;
    }

    public function updateStats($accountConnectionId)
    {

        $temp = \AccountConnection::find($accountConnectionId)
            ->content()
            ->withPivot('external_id', 'likes', 'shares')
            ->get();

        $content = array();
        foreach ($temp as $c) {
            list(, $id) = explode('_', $c->pivot->external_id);
            $content[$id] = $c;
        }

        $session = $this->getClient();
        $posts = (new FacebookRequest($session, 'GET', "/", [
            'ids' => implode(',', array_keys($content)),
            'fields' => 'likes.limit(10000),comments.limit(10000),sharedposts.limit(10000)'
        ]))
            ->execute()
            ->getGraphObject()
            ->asArray();

        $count = 0;
        foreach($posts as $post) {
            var_dump($post);

            $id = $post->id;
            if(isset($content[$id])) {
                $count++;
                $pivot = $content[$id]->pivot;

                $pivot->likes = isset($post->likes) ? count($post->likes->data) : 0;
                $pivot->shares = isset($post->sharedposts) ? count($post->sharedposts->data) : 0;
                $pivot->comments = isset($post->comments) ? count($post->comments->data) : 0;
                $pivot->save();
            }
        }

        return json_encode(['success' => 1, 'count' => $count]);
    }

}