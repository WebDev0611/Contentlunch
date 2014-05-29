<?php namespace Launch\Connections\API;

use HappyR\LinkedIn\LinkedIn;
use Illuminate\Support\Facades\Config;
use Launch\Connections\API\ConnectionConnector;

class LinkedInAPI implements Connection
{
    /**
     * A reference to the LinkedIn SDK we're using 
     * that in initialized in __construct
     * @var LinkedIn
     */
    private $linkedIn;

    /**
     * Do whatever setup we need to set up the SDK to make
     * connections to the API
     * @param array $accountConnection Settings passed from the database
     */
    public function __construct(array $accountConnection)
    {
        // $config = Config::get('services.linkedin');
        // $this->linkedIn = new LinkedIn($config['key'], $config['secret']);

        // need to pass params to please the fn definition, but actual
        // values are only needed if we are using oauth, I think...
        $this->linkedIn = new LinkedIn(null, null);
        $this->linkedIn->setAccessToken($accountConnection['settings']['token']->getAccessToken());
    }

    /**
     * Returns a list of friends/connections/followers
     * @return array List of friends/connections/followers
     */
    public function getFriends($page = 0, $perPage = 1000)
    {
        $result = $this->linkedIn->api('v1/people/~/connections:(id,headline,first-name,last-name,industry,public-profile-url)', ['count' => $perPage, 'start' => $page]);
        return $this->processResult($result);
    }

    /**
     * Post the provided content to the connections
     * @param  string $content The content to publish
     * @return Response        200 on success, an error from ConnectionConnector::responseError on failure
     */
    public function postContent($content)
    {

    }


    /**
     * Send a direct message to the IDs passed in with the provided message data
     * @param  array  $ids     Array of IDs of recipients
     * @param  array  $message Array of message details [subject, body]
     * @return Response        200 on success, an error from ConnectionConnector::responseError on failure
     */
    public function sendDirectMessage(array $ids, array $message)
    {
        $payload = [
            "recipients" => [
                "values" => []
            ],
            "subject" => $message['subject'],
            "body"    => $message['body']
        ];

        foreach ($ids as $id) {
            $payload['recipients']['values'][] = [
                "person" => [
                    "_path" => "/people/id={$id}",
                ]
            ];
        }

        $result = $this->linkedIn->api('v1/people/~/mailbox', [], 'POST', $payload);

        return $this->processResult($result);
    }

    private function processResult($result)
    {
        if (!empty($result['error'])) return ConnectionConnector::responseError(@$result['message'], @$result['status']);
        return @$result['values'] ? $result['values'] : $result;
    }
}
