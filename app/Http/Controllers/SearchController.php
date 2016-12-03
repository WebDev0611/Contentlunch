<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;

use App\Content;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $validation = $this->validateRequest($request);

        if ($validation->fails()) {
            return redirect('/')->with([
                'flash_message' => 'Invalid search term',
                'flash_message_type' => 'danger',
                'flash_message_important' => true,
            ]);
        }

        $searchTerm = $request->input('search');
        $contents = $this->searchContent($searchTerm);

        return view('search.index', compact('contents'));
    }

    private function validateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'search' => 'required'
        ]);
    }

    private function searchContent($term)
    {
        return Content::where('title', 'like', '%' . $term . '%')
            ->orWhere('body', 'like', '%' . $term . '%')
            ->get();
    }
}
