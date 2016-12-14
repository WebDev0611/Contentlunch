<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;
use App\WriterAccessPartialOrder;
use App\Helpers;

class WriterAccessUploadController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, WriterAccessPartialOrder $order)
    {
        $validation = $this->attachmentValidator($request->all());

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        return $this->handleAsyncUploads($request->file('file'));
    }

    private function handleAsyncUploads($file)
    {
        $url = Helpers::handleTmpUpload($file, true);

        return response()->json(['file' => $url]);
    }

    private function attachmentValidator($input)
    {
        return Validator::make($input, [
            'file' => 'file|max:20000',
        ]);
    }
}
