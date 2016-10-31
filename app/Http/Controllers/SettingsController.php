<?php

namespace App\Http\Controllers;

use Auth;
use View;
use Session;
use App\Http\Requests\Connection\ConnectionRequest;
use App\Http\Requests\AccountSettings\AccountSettingsRequest;
use App\Connection;
use App\Country;
use App\Provider;
use App\Helpers;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $countries = Country::dropdown();

        return View::make('settings.index', compact('user', 'countries'));
    }

    public function update(AccountSettingsRequest $request)
    {
        $this->saveUser($request);
        $this->saveUserAvatar($request);

        return redirect()->route('settingsIndex')->with([
            'flash_message' => 'Account settings updated.',
            'flash_message_type' => 'success',
            'flash_message_important' => true,
        ]);
    }

    private function saveUserAvatar(AccountSettingsRequest $request)
    {
        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $user->profile_image = Helpers::handleProfilePicture($user, $request->file('avatar'));
            $user->save();
        }
    }

    private function saveUser(AccountSettingsRequest $request)
    {
        $user = Auth::user();

        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->city = $request->input('city');
        $user->country_code = $request->input('country_code');
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->save();

        $user->account->name = $request->input('account_name');
        $user->account->save();
    }

    public function content()
    {
        $user = Auth::user();

        return View::make('settings.content', compact('user'));
    }

    public function connections()
    {
        $user = Auth::user();

        // Pulling Connection information
        $connections = $user->connections()->get();
        $activeConnectionsCount = $user->connections()->where('successful', 1)->count();

        // - Create Connection Drop Down Data
        $connectiondd = ['' => '-- Select One --'];
        $connectiondd += Provider::select('slug', 'name')
            ->where('class_name', '!=', '')
            ->orderBy('name', 'asc')
            ->distinct()
            ->lists('name', 'slug')
            ->toArray();

        return View::make('settings.connections', compact(
            'user',
            'connectiondd',
            'connections',
            'activeConnectionsCount'
        ));
    }

    public function connectionCreate(ConnectionRequest $request)
    {
        $connType = $request->input('con_type');
        $connActive = $request->input('con_active');

        // - Store the conection data
        $conn = new Connection();
        $conn->name = $request->input('con_name');
        $conn->provider_id = Provider::findBySlug($connType)->id;
        $conn->active = $connActive == 'on' ? 1 : 0;
        $conn->save();

        // - Attach to the user
        Auth::user()->connections()->save($conn);

        Session::put('connection_data', [
            'meta_data' => $request->input('api'),
            'connection_id' => $conn->id,
        ]);

        // - Lets get out of here
        return redirect()->route('connectionProvider', $connType);
    }

    public function seo()
    {
        $user = Auth::user();

        return View::make('settings.seo', compact('user'));
    }

    public function buying()
    {
        $user = Auth::user();

        return View::make('settings.buying', compact('user'));
    }
}
