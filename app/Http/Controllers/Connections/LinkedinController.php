<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;

class LinkedinController extends BaseConnectionController
{
    public function callback (Request $request) {
        if ($error = $request->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError(json_encode($request->input('error')));
        }

        $user = Socialite::driver('linkedin')->user();

        if (collect($user)->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError('There was an error with your authentication, please try again');
        }

        $tokenArray = [
            'token' => $user->token,
            'refreshToken' => $user->refreshToken,
            'expiresIn' => $user->expiresIn
        ];
        $connection = $this->saveConnection($tokenArray, 'linkedin');

        return $this->redirectWithSuccess("You've successfully connected to LinkedIn.");
    }
}
