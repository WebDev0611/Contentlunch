<?php

namespace App\Http\Controllers;

use App\Content;
use App\Export;
use App\Traits\Redirectable;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class ExportController extends Controller
{
    use Redirectable;

    public function __construct ()
    {
        // Make sure temporary export directory exists
        $exportPath = Export::exportPath();
        if(!is_dir($exportPath)) {
            File::makeDirectory($exportPath);
        }
    }

    public function content ($id, $extension)
    {
        $content = Content::findOrFail($id);

        if (Auth::user()->cant('edit', $content)) {
            return $this->danger('contents.index', "You don't have sufficient permissions to do this.");
        }

        switch ($extension) {
            case 'docx' :
                $pathToFile = Export::contentToWordDocument($content, str_slug($content->title, '-'));
                break;
            case 'pdf' :
                $pathToFile = Export::contentToPDFDocument($content, str_slug($content->title, '-'));
                break;
            default:
                return $this->danger('contents.index', "Unsupported extension.");
        }

        return response()->download($pathToFile)->deleteFileAfterSend(true);
    }
}
