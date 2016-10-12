<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use oAuth\API\WordPressAuth;

class WordpressController extends Controller
{
    public function login()
    {

    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        $wordpress = new WordPressAuth;

        dd([ 'response' => $wordpress->codeForToken($code) ]);
    }
}
