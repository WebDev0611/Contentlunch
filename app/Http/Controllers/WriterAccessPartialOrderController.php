<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\WriterAccessPartialOrder;

class WriterAccessPartialOrderController extends Controller
{
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
        session([ 'order' => $order ]);

        return redirect('/get_written/1');
    }

    private function createOrder(array $data)
    {
        $order = WriterAccessPartialOrder::create([
            'project_name' => $data['project_name'],
            'duedate' => $data['due_date'],
            'asset_type' => $data['writer_access_asset_type'],
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
    public function update(Request $request, $id)
    {
        $validation = $this->validateCurrentStep($request);
        $currentStep = $request->input('step');

        if ($validation->fails()) {
            return redirect('get_written/' . $currentStep)->with('errors', $validation->errors());
        }

        $order = $this->updateOrder($id, $request->all());

        return $this->redirectToNextStep($currentStep)->with('order', $order);
    }

    private function redirectToNextStep($currentStep)
    {
        $nextStep = $currentStep + 1;
        return redirect("get_written/$nextStep");;
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
