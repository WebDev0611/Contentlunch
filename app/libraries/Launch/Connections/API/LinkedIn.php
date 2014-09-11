<?php namespace Launch\Connections\API;

use HappyR\LinkedIn\LinkedIn;
use HappyR\LinkedIn\Http\Request;
use Illuminate\Support\Facades\Config;
use Launch\Connections\API\ConnectionConnector;
use Launch\Exception\OAuthTokenException;
use LSS\Array2XML;

/**
 * @see https://github.com/HappyR/LinkedIn-API-client/
 * @see https://developer.linkedin.com/core-concepts
 */
class LinkedInAPI extends AbstractConnection implements Connection
{
    /**
     * A reference to the LinkedIn SDK we're using
     * that in initialized in __construct
     * @var LinkedIn
     */
    private $linkedIn;

    protected $configKey = 'services.linkedin';

    /**
     * Do whatever setup we need to set up the SDK to make
     * connections to the API
     * @param array $accountConnection Settings passed from the database
     */
    public function __construct(array $accountConnection)
    {
        parent::__construct($accountConnection);

        if (!empty($accountConnection['settings'])) {

            // $config = Config::get('services.linkedin');
            // $this->linkedIn = new LinkedIn($config['key'], $config['secret']);

            // need to pass params to please the fn definition, but actual
            // values are only needed if we are using oauth, I think...
            $this->linkedIn = new LinkedIn(null, null);
            $this->linkedIn->setAccessToken($accountConnection['settings']['token']->getAccessToken());
        }
    }

    protected function getClient()
    {
        $client = new LinkedIn($this->config['key'], $this->config['secret']);
        $client->setAccessToken($this->getAccessToken());
        return $client;
    }

    /**
     * Get the external user / account id
     */
    public function getExternalId()
    {
        $me = $this->getMe();
        return $me['id'];
    }

    public function getIdentifier()
    {
        $me = $this->getMe();
        return $me['firstName'] . ' ' . $me['lastName'];
    }

    public function getMe()
    {
        if (!$this->me) {
            $client = $this->getClient();
            $this->me = $client->api('v1/people/~:(id,firstName,lastName,picture-url,public-profile-url)');
            if (isset($this->me['errorCode'])) {
                throw new OAuthTokenException($this->me['message']);
            }
        }
        return $this->me;
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

    public function getUrl()
    {
        $me = $this->getMe();
        return $me['publicProfileUrl'];
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

        $ids = array_keys($content);

        $client = $this->getClient();
        $shares = $client->api('v1/people/~/network/updates::('.implode(',', $ids).')');

        $count = 0;
        if(isset($shares['values'])) {
            foreach($shares['values'] as $share) {
                $id = $share['updateKey'];
                if(isset($content[$id])) {
                    $count++;

                    $content[$id]->pivot->likes = $share['numLikes'];
                    $content[$id]->pivot->shares = $share['updateComments']['_total'];
                    $content[$id]->pivot->save();
                }
            }
        }

        return json_encode(['success' => 1, 'count' => $count]);
    }

    /**
     * Post the provided content to the connections
     * @param  string $content The content to publish
     * @return Response
     * @see https://developer.linkedin.com/documents/share-api
     */
    public function postContent($content)
    {
        // If no image, just posting the content body as a comment
        $payload = [
            'comment' => $this->stripTags($content->body),
            'visibility' => [
                'code' => 'anyone'
            ]
        ];

        $upload = $content->upload()->first();
        if ($upload && $upload->media_type == 'image') {
            $payload['content'] = [
                'title' => $this->stripTags($content->title),
                'submitted-url' => $upload->getImageUrl('large'),
                'submitted-image-url' => $upload->getImageUrl('large')
            ];
        }

        $response = $this->linkedIn->api("v1/people/~/shares", [], 'POST', $payload);

        if (isset($response['errorCode'])) {
            return [
                'success' => false,
                'error' => $response['message'],
                'response' => $response
            ];
        }
        return [
            'success' => true,
            // this response doesn't actually return anything on success
            'response' => $response ? : true,
            'external_id' => $response['updateKey']
        ];
    }

    public function postToGroup($content, $groupID)
    {
        $payload = [
            'title' => $this->stripTags($content->title),
            'summary' => $this->stripTags($content->body),
        ];

        // See if content main upload is an image and attach it
        $upload = $content->upload()->first();
        if ($upload && $upload->media_type == 'image') {
            $payload['content'] = [
                'title' => $this->stripTags($content->title),
                'submitted-url' => $upload->getImageUrl('large'),
                'submitted-image-url' => $upload->getImageUrl('large')
            ];
        }

        $response = $this->linkedIn->api("v1/groups/{$groupID}/posts", [], 'POST', $payload);

        if (isset($response['errorCode'])) {
            return [
                'success' => false,
                'error' => $response['message'],
                'response' => $response
            ];
        }
        return [
            'success' => true,
            // this response doesn't actually return anything on success
            'response' => $response ? : true,
            'external_id' => $response['updateKey']
        ];
    }


    /**
     * Send a direct message to the IDs passed in with the provided message data
     * @param  array $ids Array of IDs of recipients
     * @param  array $message Array of message details [subject, body]
     * @param  int $contentID ID of the content to associate the guest with
     * @return Response        200 on success, an error from ConnectionConnector::responseError on failure
     */
    public function sendDirectMessage(array $friends, array $message, $contentID, $contentType, $accountID)
    {
        $results = [];
        foreach ($friends as $friend) {
            $id = $friend['id'];
            $name = $friend['name'];

            $accessCode = ConnectionConnector::makeAccessCode($id);
            $link = ConnectionConnector::makeShareLink($accessCode);

            $payload = [
                "recipients" => [
                    "values" => [[
                        "person" => [
                            "_path" => "/people/id={$id}",
                        ]
                    ]]
                ],
                "subject" => $message['subject'],
                "body" => "{$message['body']}\n\n{$link}"
            ];

            $result = $this->linkedIn->api('v1/people/~/mailbox', [], 'POST', $payload);
            $results[$id]['success'] = empty($result['error']) && !isset($result['errorCode']);

            if ($results[$id]['success']) {
                ConnectionConnector::createGuestCollaborator([
                    'connection_user_id' => $id,
                    'name' => $name,
                    'connection_id' => $this->accountConnection['connection_id'],
                    'content_id' => $contentID,
                    'account_id' => $accountID,
                    'content_type' => $contentType,
                    'access_code' => $accessCode,
                ]);
            }

            $results[$id]['id'] = $id;
            $results[$id]['raw'] = $result;
        }

        return $this->processResult($results);
    }

    public function getGroups($page = 0, $perPage = 1000)
    {
        $result = $this->linkedIn->api('v1/people/~/group-memberships'); // ['count' => $perPage, 'start' => $page]);
        return $this->processResult($result);
    }

    public function sendMessageToGroup($group, $message, $contentID, $contentType, $accountID)
    {
        $accessCode = ConnectionConnector::makeAccessCode($group['id']);
        $link = ConnectionConnector::makeShareLink($accessCode);

        $payload = [
            'title' => $message['subject'],
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

        if (empty($result['error']) && !isset($result['errorCode'])) {
            ConnectionConnector::createGuestCollaborator([
                'connection_user_id' => $group['id'],
                'name' => $group['name'],
                'connection_id' => $this->accountConnection['connection_id'],
                'content_id' => $contentID,
                'account_id' => $accountID,
                'content_type' => $contentType,
                'access_code' => $accessCode,
                'type' => 'group',
            ]);
        }

        $result['values'] = [
            $group['id'] => [
                'success' => empty($result['error']) && !isset($result['errorCode']),
                'raw' => $result,
                'id' => $group['id'],
            ],
        ];

        return $this->processResult($result);
    }

    /**
     * Handle responses for this particular API
     * @param  array $result result from an API call
     * @return Response
     */
    private function processResult($result)
    {
        if (!empty($result['error']) || isset($result['errorCode'])) return ConnectionConnector::responseError(@$result['message'], @$result['status'], $result);
        return @$result['values'] ? $result['values'] : ((!@$result['values'] && @$result['_total'] === 0) ? [] : $result);
    }
}
