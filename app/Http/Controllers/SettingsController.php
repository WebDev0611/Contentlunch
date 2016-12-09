<?php

namespace App\Http\Controllers;

use Auth;
use App\Account;
use App\Http\Requests\AccountSettings\AccountSettingsRequest;
use App\Country;
use App\Provider;
use App\Helpers;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $countries = Country::dropdown();

        return view('settings.index', compact('user', 'countries'));
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
    }

    public function content()
    {
        $user = Auth::user();

        return view('settings.content', compact('user'));
    }

    public function connections()
    {
        $user = Auth::user();
        $account = Account::selectedAccount();

        $connections = $account->connections()->get();
        $activeConnectionsCount = $account->connections()->where('active', 1)->count();

        $connectiondd = ['' => '-- Select One --'] + $this->providerDropdown();

        return view('settings.connections', compact(
            'user',
            'account',
            'connectiondd',
            'connections',
            'activeConnectionsCount'
        ));
    }

    private function providerDropdown()
    {
        return Provider::select('slug', 'name')
            ->where('class_name', '!=', '')
            ->orderBy('name', 'asc')
            ->distinct()
            ->lists('name', 'slug')
            ->toArray();
    }

    public function seo()
    {
        $user = Auth::user();

        return view('settings.seo', compact('user'));
    }

    public function buying()
    {
        $user = Auth::user();

        return view('settings.buying', compact('user'));
    }
}
