<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BuyingStage;

class BuyingStagesController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => BuyingStage::all()
        ]);
    }

    public function create(Request $request)
    {
        $buyingStage = BuyingStage::create([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);

        return response()->json([ 'data' => $buyingStage ], 201);
    }

    public function delete(Request $request, BuyingStage $buyingStage)
    {
        $buyingStage->delete();

        return response()->json([], 200);
    }
}
