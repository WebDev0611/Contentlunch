<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;

use App\Account;
use App\Content;
use App\User;
use App\Task;
use App\Idea;

class SearchController extends Controller
{
    public function __construct(Request $request)
    {
        $this->selectedAccount = Account::selectedAccount();
    }

    public function index(Request $request)
    {
        $validation = $this->validateRequest($request);

        if ($validation->fails()) {
            return $this->redirectFailedValidation();
        }

        $searchTerm = $request->input('search');

        $data = [
            'contents' => $this->searchContent($searchTerm),
            'users' => $this->searchUsers($searchTerm),
            'tasks' => $this->searchTasks($searchTerm),
            'ideas' => $this->searchIdeas($searchTerm),
            'searchTerm' => $searchTerm,
        ];

        return view('search.index', $data);
    }

    private function redirectFailedValidation()
    {
        return redirect('/')->with([
            'flash_message' => 'Invalid search term',
            'flash_message_type' => 'danger',
            'flash_message_important' => true,
        ]);
    }

    private function validateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'search' => 'required'
        ]);
    }

    private function searchContent($term)
    {
        return $this->selectedAccount
            ->contents()
            ->where('title', 'like', '%' . $term . '%')
            ->orWhere('body', 'like', '%' . $term . '%')
            ->get();
    }

    private function searchUsers($term)
    {
        return $this->selectedAccount
            ->users()
            ->where('name', 'like', '%' . $term . '%')
            ->get();
    }

    private function searchTasks($term)
    {
        return $this->selectedAccount
            ->tasks()
            ->where('name', 'like', '%' . $term . '%')
            ->orWhere('explanation', 'like', '%' . $term . '%')
            ->get();
    }

    private function searchIdeas($term)
    {
        return $this->selectedAccount
            ->ideas()
            ->where('name', 'like', '%' . $term . '%')
            ->orWhere('text', 'like', '%' . $term . '%')
            ->get();
    }
}
