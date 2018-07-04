<?php

namespace Connections\API;
use Illuminate\Support\Facades\Config;

class AbstractConnection {

	public function getUser($provider)
	{
	    $user = Socialite::driver($provider)->user();
	    return $user;
	}
}
