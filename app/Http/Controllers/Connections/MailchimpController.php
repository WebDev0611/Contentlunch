<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;
use oAuth\API\MailchimpAuth;

class MailchimpController extends BaseConnectionController
{
    public function __construct (Request $request) {
        $this->auth = new MailchimpAuth();
    }

    public function callback (Request $request) {
        if ($error = $request->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError($this->errorMessage($request->input('error')));
        }

        $code = $request->input('code');
        $token = $this->auth->codeForToken($code);

        if (collect($token)->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError('There was an error with your authentication, please try again');
        }

        $tokenArray = (array)$token;
        $connection = $this->saveConnection($tokenArray, 'mailchimp');

        return $this->redirectWithSuccess("You've successfully connected to Mailchimp.");
    }

    protected function errorMessage(Request $request, $error)
    {
        return $error == 'access_denied' ?
            'You need to authorize ContentLaunch if you want to use the Mailchimp connection' :
            $request->input('error_description');
    }
}
