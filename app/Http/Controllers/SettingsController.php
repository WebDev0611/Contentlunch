<?php

namespace App\Http\Controllers;

use App\Account;
use App\Helpers;
use App\Http\Requests\AccountSettings\AccountSettingsRequest;
use App\Presenters\CountryPresenter;
use App\Provider;
use App\User;
use Auth;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller {

    public function index ()
    {
        $user = Auth::user();
        $account = Account::selectedAccount();
        $countries = CountryPresenter::dropdown();

        return view('settings.index', compact('user', 'account', 'countries'));
    }

    public function update (AccountSettingsRequest $request)
    {
        $this->saveUser($request);
        $this->saveUserAvatar($request);

        return redirect()->route('settings.index')->with([
            'flash_message'           => 'User settings updated.',
            'flash_message_type'      => 'success',
            'flash_message_important' => true,
        ]);
    }

    private function saveUserAvatar (AccountSettingsRequest $request)
    {
        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $user->profile_image = Helpers::handleProfilePicture($user, $request->file('avatar'));
            $user->save();
        }
    }

    private function saveUser (AccountSettingsRequest $request)
    {
        $user = Auth::user();

        $this->changeUserEmail($request);
        $user->name = $request->input('name');
        $user->city = $request->input('city');
        $user->country_code = $request->input('country_code');
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->save();
    }

    public function changeUserEmail ($request)
    {
        $user = Auth::user();

        if ($request->has('email') && $request->input('email') !== $user->email) {
            $confirmation_code = str_random(30);

            $user->new_email = $request->input('email');
            $user->email_confirmation_code = $confirmation_code;
            $user->save();

            Mail::send('emails.email_verify', ['confirmation_code' => $confirmation_code], function ($message) {
                $message->from("no-reply@contentlaunch.com", "Content Launch")
                    ->to(Auth::user()->email)
                    ->subject('Verify your email address');
            });
        }
    }

    public function verifyUserEmail ($confirmationCode)
    {
        if (!$confirmationCode || !$user = User::whereEmailConfirmationCode($confirmationCode)->first()) {
            return redirect()->route('settings.index')->with([
                'flash_message'           => 'Invalid confirmation code.',
                'flash_message_type'      => 'danger',
                'flash_message_important' => true,
            ]);
        }

        $user->email = $user->new_email;
        $user->new_email = null;
        $user->email_confirmation_code = null;
        $user->save();

        return redirect()->route('settings.index')->with([
            'flash_message'           => 'User email address successfully changed!',
            'flash_message_type'      => 'success',
            'flash_message_important' => true,
        ]);
    }

    public function content ()
    {
        $user = Auth::user();
        $account = Account::selectedAccount();

        return view('settings.content', compact('user', 'account'));
    }

    public function connections ()
    {
        $user = Auth::user();
        $account = Account::selectedAccount();

        $connections = $account->connections()->active()->get();
        $activeConnectionsCount = $connections->count();

        $connectiondd = ['' => '-- Select One --'] + $this->providerDropdown();

        return view('settings.connections', compact(
            'user',
            'account',
            'connectiondd',
            'connections',
            'activeConnectionsCount'
        ));
    }

    private function providerDropdown ()
    {
        return Provider::select('slug', 'name')
            ->where('class_name', '!=', '')
            ->orderBy('name', 'asc')
            ->distinct()
            ->pluck('name', 'slug')
            ->toArray();
    }

    public function seo ()
    {
        $user = Auth::user();

        return view('settings.seo', compact('user'));
    }

    public function buying ()
    {
        $user = Auth::user();

        return view('settings.buying', compact('user'));
    }
}
