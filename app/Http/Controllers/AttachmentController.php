<?php

namespace App\Http\Controllers;

use App\Attachment;
use Storage;
use Illuminate\Http\Request;

use App\Http\Requests;

class AttachmentController extends Controller
{
    public function destroy(Attachment $attachment)
    {
        Storage::delete($attachment->filepath);
        $attachment->delete();

        return response()->json([
            'data' => true
        ]);
    }
}
