<?php

namespace App\Http\Controllers;

class CollaborateController extends Controller {

	public function index()
    {
		return view('collaborate.index');
	}

    public function indexOld()
    {
        return view('collaborate.index_old');
    }

	public function linkedin()
    {
		return view('collaborate.linkedin');
	}

	public function twitter()
    {
		return view('collaborate.twitter');
	}

	public function bookmarks()
    {
		return view('collaborate.bookmarks');
	}
}
