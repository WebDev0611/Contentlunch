<?php

namespace App\Http\Controllers;

use Auth;
use Crypt;
use File;
use Storage;
use View;
use Carbon\Carbon;

use App\Http\Requests\Connection\ConnectionRequest;
use App\Http\Requests\AccountSettings\AccountSettingsRequest;
use App\Connection;
use App\Provider;
use App\Helpers;

class SettingsController extends Controller {

    public function index()
    {
        $user = Auth::user();
        return View::make('settings.index', compact('user'));
    }

    public function update(AccountSettingsRequest $request)
    {
        $user = Auth::user();

        $user->email = $request->input('email');
        $user->name = $request->input('name');

        if ($request->hasFile('avatar')) {
            $user->profile_image = $this->handleProfilePicture($request->file('avatar'));
        }

        $user->save();

        return redirect()->route('settingsIndex')->with([
            'flash_message' => "Account settings updated.",
            'flash_message_type' => 'success',
            'flash_message_important' => true
        ]);
    }

    private function handleProfilePicture($file)
    {
        $user = Auth::user();
        $path = 'attachment/' . $user->id . '/profile/';

        // TODO: validate mime type
        $mime      = $file->getClientMimeType();

        $extension = $file->getClientOriginalExtension();
        $filename  = Helpers::slugify($user->name) . $extension;
        $timestamp = Carbon::now('UTC')->format('Ymd_His');
        $fileDoc   = $timestamp . '_' . $filename;
        $fullPath  = $path . $fileDoc;

        Storage::put($fullPath, File::get($file));

        return Storage::url($fullPath);
    }

	public function content(){
		return View::make('settings.content');

	}

    public function connections()
    {
		// Pulling Connection information
		$connections = Auth::user()->connections()->get();
		$activeConnectionsCount = Auth::user()->connections()->where('successful',1)->count();

		// - Create Connection Drop Down Data
		$connectiondd = ['' => '-- Select One --'];
		$connectiondd += Provider::select('slug','name')->where('class_name', '!=', '')->orderBy('name', 'asc')->distinct()->lists('name', 'slug')->toArray();

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
