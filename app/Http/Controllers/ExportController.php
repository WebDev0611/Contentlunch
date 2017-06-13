<?php

namespace App\Http\Controllers;

use App\Content;
use App\Export;
use App\Traits\Redirectable;
use App\WriterAccessOrder;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class ExportController extends Controller {

    use Redirectable;

    public function __construct ()
    {
        // Make sure temporary export directory exists
        $exportPath = Export::exportPath();
        if (!is_dir($exportPath)) {
            File::makeDirectory($exportPath);
        }
    }

    public function content ($id, $extension, $locationHandle = null)
    {
        $content = Content::findOrFail($id);

        if (Auth::user()->cant('edit', $content)) {
            return $this->danger('contents.index', "You don't have sufficient permissions to do this.");
        }

        switch ($extension) {
            case 'docx' :
                $pathToFile = Export::contentToWordDocument($content, str_slug($content->title, '-'));
                $mimetype = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                break;
            case 'pdf' :
                $pathToFile = Export::contentToPDFDocument($content, str_slug($content->title, '-'));
                $mimetype = 'application/pdf';
                break;
            default:
                return $this->danger('contents.index', "Unsupported extension.");
        }

        if ($locationHandle !== null) {
            $locationHandle->uploadFile($pathToFile, $content->title . '.' . $extension, $mimetype);
        }

        return response()->download($pathToFile)->deleteFileAfterSend(true);
    }

    public function order ($id, $extension)
    {
        $order = WriterAccessOrder::findOrFail($id);

        if (Auth::user()->cant('show', $order)) {
            return $this->danger('content_orders.index', "You don't have sufficient permissions to do this.");
        }

        switch ($extension) {
            case 'docx' :
                $pathToFile = Export::orderToWordDocument($order, str_slug($order->title, '-'));
                break;
            default:
                return $this->danger('content_orders.index', "Unsupported extension.");
        }

        return response()->download($pathToFile)->deleteFileAfterSend(true);
    }
}
