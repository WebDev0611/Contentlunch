<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use Launch\Connections\API\ConnectionConnector;
use Thujohn\Twitter\Twitter;

class TwitterAPI extends AbstractConnection
{

    protected $configKey = 'services.twitter';

    protected function getClient()
    {
      if ( ! $this->client) {
        $token = $this->getAccessToken();
        $secret = $this->getAccessTokenSecret();
        if ($token && $secret) {
          $this->client = new Twitter([
            'consumer_key'    => $this->config['key'],
            'consumer_secret' => $this->config['secret'],
            'token'           => $token,
            'secret'          => $secret,
          ]);
        }
      }
      return $this->client;
    }

    /**
     * Returns a list of friends/connections/followers
     * @return array List of friends/connections/followers
     */
    public function getFriends($page = 0, $perPage = 1000)
    {
        $client = $this->getClient();
        $result = $client->getFollowers([
            'count'       => $perPage,
            'format'      => 'array',
            'skip_status' => 1,
            // apparently it's -1 indexed?
            'cursor'      => $page - 1,
        ]);

        return $this->processResult(@$result['users'] ? $result['users'] : $result);
    }

    public function getIdentifier()
    {
      $me = $this->getMe();
      return $me->name .' (@'. $me->screen_name .')';
    }

    public function getMe()
    {
      if ( ! $this->me) {
        $client = $this->getClient();
        $this->me = $client->getCredentials();
      }
      return $this->me;
    }

    public function getUrl()
    {
      $me = $this->getMe();
      return 'https://twitter.com/'. $me->screen_name;
    }

    /**
     * Post the provided content to the connections
     * @param  object $content The content to publish
     * @return Response
     */
    public function postContent($content)
    {
      $client = $this->getClient();
      // Strip html tags
      $message = strip_tags($content->body);

      // If there is an attached file, upload with media
      $upload = $content->upload()->first();
      if ($upload && $upload->media_type == 'image') {
        // Twitter supports PNG, JPG and GIF up to 3 MB
        $response = $client->postTweetMedia([
          'status' => $message,
          'format' => 'array',
          'media[]' => file_get_contents($upload->getAbsPath())
        ]);
      } else {
        $response = $client->postTweet([
          'status' => $message,
          'format' => 'array'
        ]);
      }
      if ( ! empty($response['errors'])) {
        $errors = [];
        foreach ($response['errors'] as $error) {
          $errors[] = $error['message'];
        }
        return [
          'success' => false,
          'error' => implode(' ', $errors),
          'response' => $response
        ];
      }
      return [
        'success' => true,
        'response' => $response,
        'external_id' => $response['id_str']
      ];
    }

    public function updateStats($accountConnectionId) {

        $temp = \AccountConnection::find($accountConnectionId)
            ->content()
            ->withPivot('external_id', 'likes', 'shares')
            ->get();

        $content = array();
        foreach($temp as $c) {
            $content[$c->pivot->external_id] = $c;
        }

        //var_dump($content);

        $client = $this->getClient();
        $tweets = $client->query('statuses/lookup', 'GET', ['id' => implode(',', array_keys($content)), 'format' => 'array']);
        //var_dump($tweets);

        $count = 0;
        foreach($tweets as $tweet) {
            $id = $tweet['id'];
            if(isset($content[$id])) {
                $count++;

                $content[$id]->pivot->likes = $tweet['favorite_count'];
                $content[$id]->pivot->shares = $tweet['retweet_count'];
                $content[$id]->pivot->save();
            }
        }

        return json_encode(['success' => 1, 'count' => $count]);
    }

    /**
     * Send a direct message to the IDs passed in with the provided message data
     * @param  array  $ids       Array of IDs of recipients
     * @param  array  $message   Array of message details [subject, body]
     * @param  int    $contentID ID of the content to associate the guest with
     * @return Response        200 on success, an error from ConnectionConnector::responseError on failure
     */
    public function sendDirectMessage(array $friends, array $message, $contentID, $contentType, $accountID)
    {
        $client = $this->getClient();
        $results = [];
        foreach ($friends as $friend) {
            $id = $friend['id'];
            $name = $friend['name'];
            
            $accessCode = ConnectionConnector::makeAccessCode($id);
            $link       = ConnectionConnector::makeShareLink($accessCode);

            $result = $client->postDm([
                'user_id' => $id,
                'text'    => "{$message['body']}\n\n{$link}",
                'format'  => 'array'
            ]);

            $results[$id]['success'] = empty($result['errors']);

            if ($results[$id]['success']) {
                $result = ConnectionConnector::createGuestCollaborator([
                    'connection_user_id' => $id,
                    'name'               => $name,
                    'connection_id'      => $this->accountConnection['connection_id'],
                    'content_id'         => $contentID,
                    'account_id'         => $accountID,
                    'content_type'       => $contentType,
                    'access_code'        => $accessCode,
                ]);
                if (!$result) return $result;
            }

            $results[$id]['id']  = $id;
            $results[$id]['raw'] = $result;
        }

        return $this->processResult($results);
    }

    /**
     * The t.co link shortener link length gets longer over time.
     * We want to remove the current length of the link from the max
     * length of a Twitter DM, and this allows us to know that length.
     * @return array ['len' => INTEGER] where INTEGER is the length the link is going to be in the DM
     */
    public function getLinkLength()
    {
        $client = $this->getClient();
        $result = $client->getHelpConfiguration(['format' => 'array']);
        return $this->processResult(@$result['short_url_length_https'] ? ['len' => $result['short_url_length_https']] : $result);
    }

    /**
     * Handle responses for this particular API
     * @param  array    $result  result from an API call
     * @return Response
     */
    private function processResult($result)
    {
        if (!empty($result['errors'])) return ConnectionConnector::responseError(@$result['errors'][0]);
        return $result;
    }
}
