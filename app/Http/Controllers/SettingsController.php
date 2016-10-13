<?php

namespace App\Http\Controllers;

use Auth;
use Crypt;
use File;
use Storage;
use View;
use Session;
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
        $filename  = Helpers::slugify($user->name) . '.' . $extension;
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

	public function connectionCreate(ConnectionRequest $request)
    {
		$connName = $request->input('con_name');
		$connType = $request->input('con_type');
		$connActive = $request->input('con_active');

        // - Store the conection data
        $conn = new Connection;
        $conn->name = $connName;
        $conn->provider_id = Provider::findBySlug($connType)->id;
        $conn->active = $connActive == 'on' ? 1 : 0;
		$conn->save();

        // - Attach to the user
        Auth::user()->connections()->save($conn);

        Session::put('connection_data', [
            'meta_data' => $request->input('api'),
            'connection_id' => $conn->id
        ]);

		// - Lets get out of here
		return redirect()->route('connectionProvider', $connType);
	}

	public function seo() {
		return View::make('settings.seo');
	}

	public function buying() {
		return View::make('settings.buying');
	}

}
