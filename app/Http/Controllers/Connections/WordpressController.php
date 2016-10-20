<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;
use Session;
use oAuth\API\WordPressAuth;
use Connections\API\WordPressAPI;
use Redirect;

class WordpressController extends BaseConnectionController
{
    public function __construct()
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
            'flash_message' => 'Wordpress connection <strong>'.$connection->name.'</strong> created successfully.',
            'flash_message_type' => 'success',
            'flash_message_important' => true,
        ]);
    }

    protected function saveConnection(array $tokenArray, $providerSlug = null)
    {
        $metaData = $this->getSessionConnectionMetadata();

        $url = $this->formatUrl($metaData['url']);
        $blogInfo = $this->api->blogInfo($url);

        $tokenArray['blog_url'] = $url;
        $tokenArray['blog_id'] = $blogInfo->ID;

        return parent::saveConnection($tokenArray, 'wordpress');
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
