<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;

use oAuth\API\WordPressAuth;

class WordpressController extends BaseConnectionController
{
    public function login()
    {

    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        $token = (new WordPressAuth)->codeForToken($code);
        $connectionData = Session::get('connection_data');
        $metaData = $connectionData['meta_data'];

        $connection = $this->getSessionConnection();

        dd([ 'response' => $token ]);
    }
}
