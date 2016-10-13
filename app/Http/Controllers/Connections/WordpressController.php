<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;

use oAuth\API\WordPressAuth;
use Connections\API\WordPressAPI;
use Redirect;

class WordpressController extends BaseConnectionController
{
    public function __construct()
    {
        $this->auth = new WordPressAuth;
        $this->api = new WordPressAPI;
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        $token = $this->auth->codeForToken($code);

        if (collect($token)->has('error')) {
            $connection = $this->getSessionConnection();
            $connection->delete();

            $this->cleanSessionConnection();

            return redirect()->route('connectionIndex')->with([
                'flash_message' => "There was an error with your authentication, please try again",
                'flash_message_type' => 'danger',
                'flash_message_important' => true
            ]);
        }

        $connection = $this->saveConnectionSettings($token);

        return redirect()->route('connectionIndex')->with([
            'flash_message' => "Wordpress connection <strong>" . $connection->name . "</strong> created successfully.",
            'flash_message_type' => 'success',
            'flash_message_important' => true
        ]);
    }

    private function saveConnectionSettings($token)
    {
        $connectionData = Session::get('connection_data');
        $metaData = $connectionData['meta_data'];

        $url = $this->formatUrl($metaData['url']);

        $blogInfo = $this->api->blogInfo($url);

        $token->blog_url = $url;
        $token->blog_id = $blogInfo->ID;

        $connection = $this->getSessionConnection();
        $tokenArray = (array) $token;
        $connection->saveSettings($tokenArray);

        return $connection;
    }

    private function formatUrl($url)
    {
        $disallowed = [ 'http://', 'https://' ];

        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }

        return $url;
    }
}
