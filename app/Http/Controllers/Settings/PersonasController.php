<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Persona;
use App\Account;

class PersonasController extends Controller
{
    public function index()
    {
        $selectedAccount = Account::selectedAccount();

        return response()->json([
            'data' => $selectedAccount->personas
        ]);
    }

    public function create(Request $request)
    {
        $selectedAccount = Account::selectedAccount();

        $persona = $selectedAccount->personas()->create([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);

        return response()->json([ 'data' => $persona ], 201);
    }

    public function delete(Request $request, Persona $persona)
    {
        $persona->delete();

        return response()->json([], 200);
    }
}
