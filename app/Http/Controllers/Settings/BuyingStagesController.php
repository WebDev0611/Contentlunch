<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BuyingStage;
use App\Account;

class BuyingStagesController extends Controller
{
    public function index()
    {
        $selectedAccount = Account::selectedAccount();

        return response()->json([
            'data' => $selectedAccount->buyingStages
        ]);
    }

    public function create(Request $request)
    {
        $selectedAccount = Account::selectedAccount();

        $buyingStage = $selectedAccount->buyingStages()->create([
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
