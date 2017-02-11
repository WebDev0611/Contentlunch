<?php

namespace App\Http\Controllers;

use App\Helpers;
use Illuminate\Http\Request;
use Validator;

class CampaignAttachmentController extends Controller
{
    public function store(Request $request)
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
