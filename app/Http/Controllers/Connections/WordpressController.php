<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;
use Session;
use oAuth\API\WordPressAuth;
use Connections\API\WordPressAPI;
use Redirect;

class WordpressController extends BaseConnectionController
{
    public $auth;
    public $api;

    public function __construct(Request $request)
    {
        $this->auth = new WordPressAuth();
        $this->api = new WordPressAPI();
    }

    public function callback(Request $request)
    {
        if ($error = $request->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError($this->errorMessage($request, $error));
        }

        $code = $request->input('code');
        $token = $this->auth->codeForToken($code);

        if (collect($token)->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError('There was an error with your authentication, please try again');
        }

        $tokenArray = (array) $token;

        try {
            $connection = $this->saveConnection($tokenArray);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->deleteSessionConnection();
            return $this->redirectWithError('We were not able to access that blog, please verify that the JetPack plugin is installed correctly.');
        }

        return $this->redirectWithSuccess("You've successfully connected to WordPress.");
    }

    protected function errorMessage(Request $request, $error)
    {
        return $error == 'access_denied' ?
            'You need to authorize ContentLaunch if you want to use the WordPress connection' :
            $request->input('error_description');
    }

    protected function saveConnection(array $tokenArray, $providerSlug = null, $saveConnection = true)
    {
        $url = $this->getBlogUrl();
        $blogInfo = $this->api->blogInfo($url);

        $tokenArray['blog_url'] = $url;
        $tokenArray['blog_id'] = $blogInfo->ID;

        return parent::saveConnection($tokenArray, 'wordpress', $saveConnection);
    }

    private function getBlogUrl()
    {
        $metaData = $this->getSessionConnectionMetadata();

        if (!collect($metaData)->has('url')) {
            $url = session('wordpress_blog_url');
        } else {
            $url = $metaData['url'];
        }

        return $this->formatUrl($url);
    }

    private function formatUrl($url)
    {
        $disallowed = ['http://', 'https://'];

        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }

        return $url;
    }
}
