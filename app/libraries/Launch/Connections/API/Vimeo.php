<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use Vimeo\Vimeo;

class VimeoAPI extends AbstractConnection
{

    protected $configKey = 'services.vimeo';

    protected $base_url = 'https://api.vimeo.com';

    protected function getClient() {
        if (!$this->client) {
            $token = $this->getAccessToken();
            $this->client = new Vimeo($this->config['key'], $this->config['secret'], $token);
            /*$this->client = new Client([
              'base_url' => $this->base_url,
              'defaults' => [
                'headers' => [
                  'Authorization' => 'Bearer '. $token,
                  'Accept' => 'application/vnd.vimeo.*+json;version=3.2'
                ]
              ]
            ]);*/
        }

        return $this->client;
    }

    /**
     * Get the external user / account id
     */
    public function getExternalId() {
        $me = $this->getMe();

        return $me['uri'];
    }

    public function getIdentifier() {
        $me = $this->getMe();

        return $me['name'];
    }

    public function getMe() {
        if (!$this->me) {
            $client = $this->getClient();
            $response = $client->request('/me');
            $this->me = $response['body'];
        }

        return $this->me;
    }

    public function getUrl() {
        $me = $this->getMe();

        return $me['link'];
    }

    public function postContent($content) {
        $client = $this->getClient();
        $response = ['success' => false, 'response' => []];

        try {

            $videoPath = $content->upload->getAbsPath();

            $uploadResponse = $client->upload($videoPath);

            // Upload response is the uri of the new video
            // /video/[video-id]
            $parts = explode('/', $uploadResponse);
            $videoID = array_pop($parts);

            $updateResponse = $this->updateVideo($content, $videoID);

            $response['response'] = [
                'video_id' => $videoID,
                'upload' => $uploadResponse,
                'update' => $updateResponse
            ];
            $response['success'] = true;
            $response['external_id'] = $videoID;
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['response'] = $uploadResponse;
            $response['error'] = $e->getMessage();
        }

        return $response;
    }

    public function updateVideo($content, $videoID) {
        $client = $this->getClient();

        // Do a PATCH request to update video's metadata
        return $client->request('/videos/' . $videoID, [
            'name' => $content->title,
            'description' => $content->body,
            'privacy.view' => 'anybody',
            'privacy.embed' => 'public',
            'review_link' => 'true'
        ], 'PATCH');
    }

    public function updateStats($accountConnectionId) {

        $content = \AccountConnection::find($accountConnectionId)
            ->content()
            ->withPivot('external_id', 'likes', 'shares')
            ->get();

        $client = $this->getClient();

        $count = 0;
        foreach ($content as $post) {
            $pivot = $post->pivot;

            $response = $client->request("/videos/".$pivot->external_id);

            if($response['status'] == 200) {
                $count++;
                $response = $response['body'];

                $pivot->likes = $response['metadata']['connections']['likes']['total'];
                $pivot->comments = $response['metadata']['connections']['comments']['total'];
                $pivot->views = $response['stats']['plays'];
                $pivot->save();
            }

        }

        return json_encode(['success' => 1, 'count' => $count]);
    }

}