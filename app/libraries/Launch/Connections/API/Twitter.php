<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use Launch\Connections\API\ConnectionConnector;
use Thujohn\Twitter\Twitter;

class TwitterAPI implements Connection
{
    /**
     * A reference to the Twitter SDK we're using 
     * that in initialized in __construct
     * @var Twitter
     */
    private $twitter;

    /**
     * Do whatever setup we need to set up the SDK to make
     * connections to the API
     * @param array $accountConnection Settings passed from the database
     */
    public function __construct(array $accountConnection)
    {
        $config = Config::get('services.twitter');

        $this->twitter = new Twitter([
            'consumer_key'    => $config['key'],
            'consumer_secret' => $config['secret'],
            'token'           => $accountConnection['settings']['token']->getAccessToken(),
            'secret'          => $accountConnection['settings']['token']->getAccessTokenSecret(),
        ]);
    }

    /**
     * Returns a list of friends/connections/followers
     * @return array List of friends/connections/followers
     */
    public function getFriends($page = 0, $perPage = 1000)
    {
        $result = $this->twitter->getFollowers([
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
     * @param  string $content The content to publish
     * @return Response        200 on success, an error from ConnectionConnector::responseError on failure
     */
    public function postContent($content)
    {
        // remember the `'format' => 'array'` param
    }


    /**
     * Send a direct message to the IDs passed in with the provided message data
     * @param  array  $ids     Array of IDs of recipients
     * @param  array  $message Array of message details [subject, body]
     * @return Response        200 on success, an error from ConnectionConnector::responseError on failure
     */
    public function sendDirectMessage(array $ids, array $message)
    {
        $results = [];
        foreach ($ids as $id) {
            /**
             * Parameters :
             * - user_id
             * - screen_name
             * - text
             */
            $result = $this->twitter->postDm([
                'user_id' => $id,
                'text'    => $message['body'],
                'format'  => 'array'
            ]);

            $results[$id] = empty($result['errors']);
        }

        return $this->processResult($results);
    }

    private function processResult($result)
    {
        if (!empty($result['errors'])) return ConnectionConnector::responseError(@$result['errors'][0]);
        return $result;
    }
}
