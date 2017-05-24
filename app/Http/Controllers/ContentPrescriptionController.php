<?php

namespace App\Http\Controllers;

use App\ContentPrescription;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class ContentPrescriptionController extends Controller {


    public function showPrescription (Request $request)
    {
        $validation = $this->prescriptionValidator($request->all());

        if ($validation->fails()) {
            return redirect()->route('prescription')->with('errors', $validation->errors());
        }

        $prescription = ContentPrescription::whereGoal($request->input('goal'))
            ->whereBudget($request->input('monthly-budget'))
            ->whereCompanyType($request->input('company-type'))
            ->first();

        return view('plan.prescription_results', ['contentPackage' => $prescription->content_package]);
    }

    private function prescriptionValidator($input)
    {
        return Validator::make($input, [
            'goal' => 'required',
            'monthly-budget' => 'required'
        ]);
    }
}
