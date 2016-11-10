<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\WriterAccessPartialOrder;

class WriterAccessPartialOrderController extends Controller
{
    protected $steps = [
        1 => 'orderSetup',
        2 => 'orderAudience',
        3 => 'orderReview'
    ];

    public function orderSetup(Request $request, WriterAccessPartialOrder $order)
    {
        return view('content.get_written_1', compact('order'));
    }

    public function orderAudience(Request $request, WriterAccessPartialOrder $order)
    {
        return view('content.get_written_2', compact('order'));
    }

    public function orderReview(Request $request, WriterAccessPartialOrder $order)
    {
        return view('content.get_written_3', compact('order'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->creationValidator($request->all());

        if ($validator->fails()) {
            return redirect('/create')->with('errors', $validator->errors());
        }

        $order = $this->createOrder($request->all());

        return redirect()->route('orderSetup', $order->id);
    }

    private function createOrder(array $data)
    {
        $order = WriterAccessPartialOrder::create([
            'project_name' => $data['project_name'],
            'duedate' => $data['due_date'],
            'asset_type_id' => $data['writer_access_asset_type'],
            'wordcount' => $data['writer_access_word_count'],
            'writer_level' => $data['writer_access_writer_level'],
        ]);

        $order->user()->associate(Auth::user())->save();

        return $order;
    }

    /**
     * Validator for the first step of the Writer Access flow.
     *
     * @param  array  $data Data from the request
     * @return Validator    Validator object
     */
    protected function creationValidator(array $data)
    {
        return Validator::make($data, [
            'project_name' => 'required|max:255',
            'due_date' => 'required',
            'writer_access_asset_type' => 'required',
            'writer_access_word_count' => 'required',
            'writer_access_writer_level' => 'required'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $orderId)
    {
        $validation = $this->validateCurrentStep($request);
        $currentStep = $request->input('step');

        if ($validation->fails()) {
            return redirect()
                ->route($this->steps[$currentStep], $orderId)
                ->with('errors', $validation->errors());
        }

        $order = $this->updateOrder($orderId, $request->all());

        return $this->redirectToNextStep($currentStep, $orderId);
    }

    private function redirectToNextStep($currentStep, $orderId)
    {
        $nextStep = $currentStep + 1;
        return redirect()->route($this->steps[$nextStep], $orderId);
    }

    private function updateOrder($orderId, array $data)
    {
        $order = WriterAccessPartialOrder::find($orderId);
        $order->update($data);
        $order->save();

        return $order;
    }

    private function validateCurrentStep(Request $request)
    {
        $currentStep = $request->input('step');

        switch ($currentStep) {
            case 1: return $this->validateStepOne($request->all());
            case 2: return $this->ValidateStepTwo($request->all());
            default:
                break;
        }
    }

    private function validateStepOne(array $data)
    {
        return Validator::make($data, [
            'content_title' => 'required|max:255',
            'instructions' => 'required',
            'narrative_voice' => 'required'
        ]);
    }

    private function validateStepTwo(array $data)
    {
        return Validator::make($data, [
            'target_audience' => 'required',
            'tone_voice' => 'required'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
