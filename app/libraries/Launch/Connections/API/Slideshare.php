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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
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

    }

    public function getIdentifier() {
        return null;
    }

    // No api endpoint really returns user data
    // Get user tags to test connectivity status
    public function getMe() {
        $this->getUserTags();

        return null;
    }

    public function getUrl() {
        return null;
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
        $response = $this->getRequest('/get_user_tags');
        echo $response;
        die;
    }

    /**
     * @see http://www.tumblr.com/docs/en/api/v2#posting
     */
    public function postContent($content) {
        $response = ['success' => true, 'response' => []];

        return $response;
    }

}