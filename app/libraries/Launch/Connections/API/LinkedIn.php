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
    private $accountConnection;

    /**
     * Do whatever setup we need to set up the SDK to make
     * connections to the API
     * @param array $accountConnection Settings passed from the database
     */
    public function __construct(array $accountConnection)
    {
        $this->accountConnection = $accountConnection;

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
        $result = $this->linkedIn->api('v1/people/~/connections:(id,headline,first-name,last-name,industry,public-profile-url)'); //, ['count' => $perPage, 'start' => $page]);
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
     * @param  array  $ids       Array of IDs of recipients
     * @param  array  $message   Array of message details [subject, body]
     * @param  int    $contentID ID of the content to associate the guest with
     * @return Response        200 on success, an error from ConnectionConnector::responseError on failure
     */
    public function sendDirectMessage(array $friends, array $message, $contentID, $contentType)
    {
        $results = [];
        foreach ($friends as $id => $name) {
            $accessCode = ConnectionConnector::makeAccessCode($id);
            $link       = ConnectionConnector::makeShareLink($accessCode);

            $payload = [
                "recipients" => [
                    "values" => [[
                        "person" => [
                            "_path" => "/people/id={$id}",
                        ]
                    ]]
                ],
                "subject" => $message['subject'],
                "body"    => "{$message['body']}\n\n{$link}"
            ];

            $result = $this->linkedIn->api('v1/people/~/mailbox', [], 'POST', $payload);
            $results[$id]['success'] = empty($result['error']) && !isset($result['errorCode']);

            if ($results[$id]['success']) {
                ConnectionConnector::createGuestCollaborator([
                    'connection_user_id' => $id,
                    'name'               => $name,
                    'connection_id'      => $this->accountConnection['connection_id'],
                    'content_id'         => $contentID,
                    'content_type'       => $contentType,
                    'access_code'        => $accessCode,
                ]);
            }

            $results[$id]['raw'] = $result;
        }

        return $this->processResult($results);
    }

    public function getGroups($page = 0, $perPage = 1000)
    {
        $result = $this->linkedIn->api('v1/people/~/group-memberships'); // ['count' => $perPage, 'start' => $page]);
        return $this->processResult($result);
    }

    public function sendMessageToGroup($group, $message, $contentID, $contentType)
    {
        $accessCode = ConnectionConnector::makeAccessCode($group['id']);
        $link       = ConnectionConnector::makeShareLink($accessCode);

        $payload = [
            'title'   => $message['subject'],
            'summary' => "{$message['body']}\n\n{$link}"
        ];

        // <post>
        //     <title>New Group Discussion</title>
        //     <summary>What does everyone think about platform development?</summary>
        //     <content>
        //         <submitted-url>http://developer.linkedin.com/forum</submitted-url>
        //         <submitted-image-url>http:/www.example.com/linkedin.png</submitted-image-url>
        //         <title>Build the Professional Web with LinkedIn</title>
        //         <description>A great resource for finding documentation and answers related to developing on the LinkedIn Platform</description>
        //     </content>
        // </post>

        $result = $this->linkedIn->api("v1/groups/{$group['id']}/posts", [], 'POST', $payload);

        if (empty($result['error']) && !isset($result['errorCode'])) ConnectionConnector::createGuestCollaborator([
            'connection_user_id' => $group['id'],
            'name'               => $group['name'],
            'connection_id'      => $this->accountConnection['connection_id'],
            'content_id'         => $contentID,
            'content_type'       => $contentType,
            'access_code'        => $accessCode,
            'type'               => 'group',
        ]);

        $result['values'] = [$group['id'] => empty($result['error']) && !isset($result['errorCode'])];
        return $this->processResult($result);
    }

    /**
     * Handle responses for this particular API
     * @param  array    $result  result from an API call
     * @return Response
     */
    private function processResult($result)
    {
        if (!empty($result['error']) || isset($result['errorCode'])) return ConnectionConnector::responseError(@$result['message'], @$result['status'], $result);
        return @$result['values'] ? $result['values'] : ((!@$result['values'] && @$result['_total'] === 0) ? [] : $result);
    }
}
