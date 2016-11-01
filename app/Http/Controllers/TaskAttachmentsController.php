<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers;

class TaskAttachmentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $this->attachmentValidator($request->all());

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $fileUrl = Helpers::handleTmpUpload($request->file('file'), true);

        return response()->json([ 'file' => $url ]);
    }

    private function attachmentValidator($input)
    {
        return Validator::make($input, [
            'file' => 'file|max:20000'
        ]);
    }
}
