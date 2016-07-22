<?php

namespace App\Http\Controllers;

use View;
use Auth;
use App\Http\Requests\Connection\ConnectionRequest;

class SettingsController extends Controller {

	public function index(){
		return View::make('settings.index');
	}

	public function content(){
		return View::make('settings.content');

	}

	public function connections(){
		$connectionType = [
			'' => '-- Select One --',
			'wordpress' => 'Wordpress',
			'facebook' => 'Facebook',
		];
		return View::make('settings.connections', compact('connectionType'));

	}	

	public function connectionCreate(ConnectionRequest $request){
		dd($request->input('connection_type'));


		// - Lets get out of here
		return redirect()->route('connectionIndex')->with([
		    'flash_message' => 'You have created a new connection.',
		    'flash_message_type' => 'success',
		    'flash_message_important' => true
		]);

	}

	public function seo(){
		return View::make('settings.seo');

	}

	public function buying(){
		return View::make('settings.buying');

	}

}
