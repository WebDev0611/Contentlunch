<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;

use App\Content;
use App\User;
use App\Task;
use App\Idea;

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

        $data = [
            'contents' => $this->searchContent($searchTerm),
            'users' => $this->searchUsers($searchTerm),
            'tasks' => $this->searchTasks($searchTerm),
            'ideas' => $this->searchIdeas($searchTerm),
        ];

        return view('search.index', $data);
    }

    private function validateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'search' => 'required'
        ]);
    }

    private function searchContent($term)
    {
        // Search content the user has access to
        // return Content::where('title', 'like', '%' . $term . '%')
        //     ->orWhere('body', 'like', '%' . $term . '%')
        //     ->get();
        return Content::all();
    }

    private function searchUsers($term)
    {
        // return User::where('name', 'like', '%' . $term . '%')
        //     ->get();
        return User::all();
    }

    private function searchTasks($term)
    {
        return Task::all();
    }

    private function searchIdeas($term)
    {
        return Idea::all();
    }
}
