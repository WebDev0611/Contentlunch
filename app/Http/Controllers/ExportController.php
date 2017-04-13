<?php

namespace App\Http\Controllers;

use App\Account;
use App\Content;
use App\Export;
use App\Traits\Redirectable;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    use Redirectable;

    public function content ($id, $extension)
    {
        $content = Content::findOrFail($id);

        if (Auth::user()->cant('edit', $content)) {
            return $this->danger('contents.index', "You don't have sufficient permissions to do this.");
        }

        $pathToFile = Export::htmlToWordDocument($content->body, str_slug($content->title, '-'));

        return response()->download($pathToFile)->deleteFileAfterSend(true);
    }
}
