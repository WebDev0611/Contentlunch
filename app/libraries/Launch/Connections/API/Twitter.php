<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use Launch\Connections\API\ConnectionConnector;
use Thujohn\Twitter\Twitter;

class TwitterAPI extends AbstractConnection
{

    protected $configKey = 'services.twitter';

    protected $meData = null;

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

    public function getMe()
    {
      if ( ! $this->meData) {
        $client = $this->getClient();
        $this->meData = $client->getCredentials();
      }
      return $this->meData;
    }

    public function getIdentifier()
    {
      $user = $this->getMe();
      if ($user) {
        return $user->name .' (@'. $user->screen_name .')';
      }
      return null;
    }

    public function getUrl()
    {
      $user = $this->getMe();
      if ($user) {
        return 'https://twitter.com/'. $user->screen_name;
      }
      return null;
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
      $response = $client->postTweet([
        'status' => $message,
        'format' => 'array'
      ]);
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
        'response' => $response
      ];
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
