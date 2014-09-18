<?php namespace Launch\Connections\API;

use Launch\Exception\OAuthTokenException;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class SlideshareAPI extends AbstractConnection
{

    protected $base_url = 'https://www.slideshare.net/api/2';

    protected $configKey = 'services.slideshare';

    protected function getClient() {
        if (!$this->client) {
            $this->client = new Client([
                'base_url' => $this->base_url,
                'defaults' => [
                    'config' => [
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => false
                        ]
                    ],
                ]
            ]);
        }

        return $this->client;
    }

    protected function getRequest($url, $params = []) {
        $creds = $this->getUserCredentials();
        $time = time();
        $params = $params + [
                'username' => $creds['username'],
                'password' => $creds['password'],
                'api_key' => $this->config['key'],
                'ts' => $time,
                'hash' => sha1($this->config['secret'] . $time)
            ];

        return file_get_contents($this->base_url . $url . '?' . http_build_query($params));
    }

    protected function postRequest($url, $params = []) {
        $creds = $this->getUserCredentials();
        $time = time();
        $params = $params + [
                'username' => $creds['username'],
                'password' => $creds['password'],
                'api_key' => $this->config['key'],
                'ts' => $time,
                'hash' => sha1($this->config['secret'] . $time)
            ];

        $ch = curl_init($this->base_url . $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * Get the external user / account id
     */
    public function getExternalId() {
        return $this->accountConnection['settings']['username'];
    }

    public function getIdentifier() {
        return $this->accountConnection['settings']['username'];
    }

    // No api endpoint really returns user data
    // Get user tags to test connectivity status
    public function getMe() {
        $response = $this->getUserTags();

        if(strpos($response, 'SlideShareServiceError') === false) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getUrl() {
        return 'http://www.slideshare.net/' . $this->accountConnection['settings']['username'];
    }

    public function getUserCredentials() {
        if (empty($this->accountConnection['settings']['username']) ||
            empty($this->accountConnection['settings']['password'])
        ) {
            throw new OAuthTokenException('Invalid connection, missing credentials');
        }

        return $this->accountConnection['settings'];
    }

    public function getUserTags() {
        return $this->getRequest('/get_user_tags');
    }


    public function postContent($content) {

        $title = $content->title;
        $description = $this->stripTags($content->body);
        $upload_path = $content->upload()->first()->getAbsPath();

        $params = [
            'slideshow_title' => $title,
            'slideshow_description' => $description,
            'slideshow_srcfile' => $this->curlFileCreate($upload_path)
        ];

        $response = $this->postRequest('/upload_slideshow', $params);

        $response_xml = simplexml_load_string($response);
        $response_json = json_encode($response_xml);
        $response_array = json_decode($response_json, true);

        if(strpos($response, 'SlideShareServiceError') === false) {
            return [
                'success' => true,
                'response' => $response_array,
                'external_id' => $response_array['SlideShowID']
            ];
        }
        else {
            return [
                'success' => false,
                'response' => $response_array,
                'error' => $response_array['Message']
            ];
        }
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

        $posts = $this->getRequest('/get_slideshows_by_user', [
            'username_for' => $this->accountConnection['settings']['username'],
            'get_unconverted' => 1,
            'detailed' => 1
        ]);

        $posts_xml = simplexml_load_string($posts);
        $posts_json = json_encode($posts_xml);
        $posts_array = json_decode($posts_json, true);

        $count = 0;
        foreach($posts_array['Slideshow'] as $post) {
            //var_dump($post);

            $id = $post['ID'];
            if(isset($content[$id])) {
                $count++;
                $pivot = $content[$id]->pivot;

                $pivot->downloads = $post['NumDownloads'];
                $pivot->views = $post['NumViews'];
                $pivot->comments = $post['NumComments'];
                $pivot->likes = $post['NumFavorites'];
                $pivot->save();
            }
        }

        return json_encode(['success' => 1, 'count' => $count]);
    }

}