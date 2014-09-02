<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use Soundcloud\Service as SoundcloudSDK;
use Soundcloud\Exception\InvalidHttpResponseCodeException;

/**
 * @see https://developers.soundcloud.com/docs/api/reference
 */
class SoundcloudAPI extends AbstractConnection
{

    protected $configKey = 'services.soundcloud';

    protected function getClient() {
        if (!$this->client) {
            $this->client = new SoundcloudSDK($this->config['key'], $this->config['secret']);
            $this->client->setAccessToken($this->getAccessToken());
        }

        return $this->client;
    }

    /**
     * Get the external user / account id
     */
    public function getExternalId() {
        $me = $this->getMe();

        return $me->id;
    }

    public function getIdentifier() {
        $me = $this->getMe();

        return $me->username;
    }

    public function getMe() {
        if (!$this->me) {
            $client = $this->getClient();
            $response = $client->get('me');
            $this->me = json_decode($response);
        }

        return $this->me;
    }

    public function getUrl() {
        $me = $this->getMe();

        return $me->permalink_url;
    }

    public function postContent($content) {
        $client = $this->getClient();
        $response = ['success' => true, 'response' => []];
        // Build the track
        $track = [
            'track[title]' => $this->stripTags($content->title),
            'track[tags]' => 'tags separated by space',
            //'track[asset_data]' => new \CURLFile('/absolute/path/to/track.mp3')
        ];
        $trackTags = [];
        if ($content->tags) {
            foreach ($content->tags as $tag) {
                $trackTags[] = trim($tag->tag);
            }
        }
        $track['track[tags]'] = implode(' ', $trackTags);
        // Get absolute path to the audio upload
        $upload = '/' . base_path() . $content->upload['path'] . $content->upload['filename'];
        // CURLFile is PHP 5.5, we're running 5.4
        //$track['track[asset_data]'] = new \CURLFile($upload);
        $track['track[asset_data]'] = '@'. $upload;
        try {
            $apiResponse = json_decode($client->post('tracks', $track), true);
            $response['success'] = true;
            $response['response'] = $apiResponse;
            $response['external_id'] = $apiResponse['id'];
        } catch (InvalidHttpResponseCodeException $e) {
            $response['success'] = false;
            $response['response'] = $e->getHttpBody();
            $response['error'] = $e->getMessage();
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['error'] = $e->getMessage();
        }

        return $response;
    }

    public function updateStats($accountConnectionId) {
        $temp = \AccountConnection::find($accountConnectionId)
            ->content()
            ->withPivot('external_id', 'likes', 'shares')
            ->get();

        $content = array();
        foreach ($temp as $c) {
            $content[$c->pivot->external_id] = $c;
        }

        $ids = array_keys($content);
        $client = $this->getClient();
        $tracks = json_decode($client->get('tracks', ['ids' => implode(',', $ids)]), true);

        $count = 0;
        foreach($tracks as $track) {
            var_dump($track);

            $id = $track['id'];
            if(isset($content[$id])) {
                $count++;
                $pivot = $content[$id]->pivot;

                $pivot->likes = isset($track['favoritings_count']) ? $track['favoritings_count'] : 0;
                $pivot->shares = isset($track['shared_to_count']) ? $track['shared_to_count'] : 0;
                $pivot->comments = isset($track['comment_count']) ? $track['comment_count'] : 0;
                $pivot->downloads = isset($track['comment_count']) ? $track['download_count'] : 0;
                $pivot->views = isset($track['comment_count']) ? $track['playback_count'] : 0;
                $pivot->save();
            }
        }

        return json_encode(['success' => 1, 'count' => $count]);
    }
}