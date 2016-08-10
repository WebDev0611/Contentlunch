<?php

namespace App\Http\Controllers;

use View;
use Auth;
use App\Http\Requests\Connection\ConnectionRequest;
use Crypt;
use App\Provider;
use App\Connection;

class SettingsController extends Controller {

	public function index(){
		return View::make('settings.index');
	}

	public function content(){
		return View::make('settings.content');

	}

	public function connections(){
		// Pulling Connection information
		$connections = Auth::user()->connections()->get();
		$activeConnectionsCount = Auth::user()->connections()->where('successful',1)->count();

		// - Create Connection Drop Down Data
		$connectiondd = ['' => '-- Select One --'];
		$connectiondd += Provider::select('slug','name')->orderBy('name', 'asc')->distinct()->lists('name', 'slug')->toArray();

		return View::make('settings.connections', compact('connectiondd', 'connections', 'activeConnectionsCount'));

	}	

	public function connectionCreate(ConnectionRequest $request){
		// - Standard Inputs
		$connName = $request->input('con_name');
		$connType = $request->input('con_type');
		$connActive = $request->input('con_active');
		$api = $request->input('api');

		// - Loop over the dynamic api form fields
		// -- the key will be the API key in the db
		$dataArray = [];
		foreach($request->input('api') as $key => $value){
			// - if the input key contains the word password lets bcrypt that data
			// -- secure'ish
			if (strpos($key, 'password') !== false) {
				$dataArray[$key] = Crypt::encrypt($value);
			}
			else {
				$dataArray[$key] = $value;
			}
		}

		// - Store the conection data
		$conn = new Connection;
		$conn->name = $connName;
		$conn->provider_id = Provider::findBySlug($connType)->id; 
		$conn->active = ($connActive = 'on' ? 1 : 0);
		$conn->settings = json_encode($dataArray);

		// -- Need to think of a dynamic way to do this
		// - Hacky way to test if URL is valid, if not lets alert user
		// --- BROKEN --
		/*$ch = @get_headers($apiUrl);
		$response = $ch[0];
		if(strpos($response,"200"))  {
		   $conn->successful = 1;
		}*/
		// --- BROKEN --
		
		$conn->save();

		// - Attach to the user
		Auth::user()->connections()->save($conn);

		// - Lets get out of here
		return redirect()->route('connectionIndex')->with([
		    'flash_message' => 'You have created a connection for '.$connName.'.',
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
