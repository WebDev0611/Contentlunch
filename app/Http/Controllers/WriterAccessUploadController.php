<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
        $file = $this->castToUploadedFile($request->file('file'));
        $newFileName = $file->getFilename().".".$file->clientExtension();
        $orderDetails = $order->getOriginal();
        $orderDetails['file'] = $newFileName;

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        // Intercept this upload if it is a bulk order document.

        // Move it to tmp storage since we'll only need it once.
        $file->move("/tmp", $newFileName);
        $order->bulk_file = "/tmp/".$newFileName;
        $order->save();
        return response()->json(["file"=>"/tmp/".$newFileName]);

        //return $this->handleAsyncUploads($file);
    }

    private function castToUploadedFile(UploadedFile $file){
        return $file;
    }

/*    private function handleAsyncUploads($file)
    {
        $url = Helpers::handleTmpUpload($file, true);

        return response()->json(['file' => $url]);
    }*/

    private function attachmentValidator($input)
    {
        return Validator::make($input, [
            'file' => 'file|max:20000',
        ]);
    }
}
