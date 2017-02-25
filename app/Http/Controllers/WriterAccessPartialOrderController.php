<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Storage;

use App\Helpers;
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
        $showBulkOrderOption = env("WRITER_ACCESS_BULK_ORDERS", false);

        return view('content.get_written_1', compact('order', 'showBulkOrderOption'));
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

        $params = $request->all();

        $params["due_date"] = Carbon::createFromFormat("m-d-Y", $params["due_date"])->format("Y-m-d");

        if ($validator->fails()) {
            return redirect('/create')->with('errors', $validator->errors());
        }

        $order = $this->createOrder($params);

        return redirect()->route('orderSetup', $order->id);
    }

    private function createOrder(array $data)
    {
        $order = WriterAccessPartialOrder::create([
            'duedate' => $data['due_date'],
            'asset_type_id' => $data['writer_access_asset_type'],
            'wordcount' => $data['writer_access_word_count'],
            'writer_level' => $data['writer_access_writer_level'],
            'order_count' => $data['writer_access_order_count'],
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
            'due_date' => 'required',
            'writer_access_asset_type' => 'required',
            'writer_access_word_count' => 'required',
            'writer_access_writer_level' => 'required',
            'writer_access_order_count' => 'required'
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

        if (collect($data)->has('attachments')) {
            foreach ($data['attachments'] as $fileUrl) {
                $newUrl = $this->moveFileToUserFolder($fileUrl, Helpers::userFilesFolder());
                $order->uploads()->create([
                    'file_path' => $newUrl
                ]);
            }
        }

        $order->update($data);
        $order->save();

        return $order;
    }

    private function moveFileToUserFolder($fileUrl, $userFolder)
    {
        $fileName = substr(strstr($fileUrl, '_tmp/'), 5);
        $newPath = $userFolder.$fileName;
        $s3Path = Helpers::s3Path($fileUrl);
        Storage::move($s3Path, $newPath);

        return Storage::url($newPath);
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
        $order = WriterAccessPartialOrder::find($data['order_id']);

        $validationArray = [
            'narrative_voice' => 'required'
        ];

        if($order->order_count === 1){
            $validationArray['content_title'] = 'required|max:255';
            $validationArray['instructions'] = 'required';
        }

        return Validator::make($data, $validationArray);
    }

    private function validateStepTwo(array $data)
    {
        return Validator::make($data, [
            'target_audience' => 'required',
            'tone_voice' => 'required'
        ]);
    }
}
