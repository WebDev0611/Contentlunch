<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Persona;

class PersonasController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Persona::all()
        ]);
    }

    public function create(Request $request)
    {
        $persona = Persona::create([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);

        return response()->json([ 'data' => $persona ], 201);
    }

    public function delete(Request $request, $persona)
    {
        $persona->delete();

        return response()->json([], 200);
    }
}
