<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;
use Session;
use oAuth\API\WordPressAuth;
use Connections\API\WordPressAPI;
use Redirect;

class WordpressController extends BaseConnectionController
{
    public function __construct(Request $request)
    {
        $this->auth = new WordPressAuth();
        $this->api = new WordPressAPI();
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        $token = $this->auth->codeForToken($code);
        $redirectRoute = $this->redirectRoute();

        if (collect($token)->has('error')) {
            $connection = $this->getSessionConnection();

            if ($connection) {
                $connection->delete();
            }

            $this->cleanSessionConnection();

            return redirect()->route($redirectRoute)->with([
                'flash_message' => 'There was an error with your authentication, please try again',
                'flash_message_type' => 'danger',
                'flash_message_important' => true,
            ]);
        }

        $tokenArray = (array) $token;
        $connection = $this->saveConnection($tokenArray);

        return redirect()->route($redirectRoute)->with([
            'flash_message' => 'Wordpress connection '.$connection->name.' created successfully.',
            'flash_message_type' => 'success',
            'flash_message_important' => true,
        ]);
    }

    protected function saveConnection(array $tokenArray, $providerSlug = null)
    {
        $url = $this->getBlogUrl();
        $blogInfo = $this->api->blogInfo($url);

        $tokenArray['blog_url'] = $url;
        $tokenArray['blog_id'] = $blogInfo->ID;

        return parent::saveConnection($tokenArray, 'wordpress');
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
