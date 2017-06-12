<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class WriterAccessOrdersController extends Controller
{
    public function getOrders(Request $request)
    {
        $user = $request->user();

        if(!$user) {
            return response()->json('You don\'t have permission to access this resource.');
        }

        return $user->writerAccessOrders()->with('writer')->orderBy('updated_at', 'desc')->get();
    }
}
