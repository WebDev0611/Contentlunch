<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use View;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * Formats the response to give either a JSON response for api URIs or a view with data attached.
     *
     * @param $data
     *
     * @return mixed
     */
    public function formatResponse($data = array())
    {
        return strpos(strtolower($_SERVER['REQUEST_URI']), 'api') !== false ?
            $data :
            View::make($this->contentView, $data);
    }

    public function isGlobalAdmin()
    {
        return Auth::user()->is_admin == 1;
    }
}
