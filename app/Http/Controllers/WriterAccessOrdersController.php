<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;

use App\Http\Requests;

class WriterAccessOrdersController extends Controller
{
    public function getOrders(Request $request)
    {
        $user = $request->user();

        if(!$user) {
            return response()->json('You don\'t have permission to access this resource.');
        }

        return $user->writerAccessOrders;
    }
}
