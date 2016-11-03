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
        if ($error = $request->has('error')) {
            $errorMessage = $error == 'access_denied' ?
                'You need to authorize ContentLaunch if you want to use the WordPress connection' :
                $request->input('error_description');

            return $this->redirectWithError($errorMessage);
        }

        $code = $request->input('code');
        $token = $this->auth->codeForToken($code);

        if (collect($token)->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError('There was an error with your authentication, please try again');
        }
        $tokenArray = (array) $token;
        $connection = $this->saveConnection($token);

        return $this->redirectWithSuccess('Wordpress connection '.$connection->name.' created successfully.');
    }

    public function cleanSessionConnection()
    {
        $connection = $this->getSessionConnection();
        if ($connection) {
            $connection->delete();
        }
        Session::forget('connection_data');
    }

    private function redirectWithSuccess($message)
    {
        return redirect()->route($this->redirectRoute())->with([
            'flash_message' => $message,
            'flash_message_type' => 'success',
            'flash_message_important' => true,
        ]);
    }

    private function redirectWithError($message)
    {
        return redirect()->route($this->redirectRoute())->with([
            'flash_message' => $message,
            'flash_message_type' => 'danger',
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
