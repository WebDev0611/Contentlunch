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
            'contents' => Content::search($searchTerm, $this->selectedAccount),
            'users' => User::search($searchTerm, $this->selectedAccount),
            'tasks' => Task::search($searchTerm, $this->selectedAccount),
            'ideas' => Idea::search($searchTerm, $this->selectedAccount),
            'searchTerm' => $searchTerm,
            'selectedAccount' => $this->selectedAccount,
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
}
