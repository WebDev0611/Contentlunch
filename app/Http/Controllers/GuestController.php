<?php

namespace App\Http\Controllers;

use App\AccountInvite;
use App\Http\Requests;
use App\Http\Requests\Onboarding\InvitedAccountRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, AccountInvite $guestInvite)
    {
        if (Auth::check()) {
            return $this->handleExistingUser($guestInvite, Auth::user());
        }

        $data = [
            'user' => new User,
            'avatarUrl' => session('avatar_temp_url'),
            'accountName' => $guestInvite->account->name,
            'guestEmail' => $guestInvite->email,
            'guestInvite' => $guestInvite,
        ];

        return view('guests.create', $data);
    }

    public function handleExistingUser(AccountInvite $guestInvite, $user)
    {
        $guestInvite->attachUser($user);

        $this->createNewUserSession($user);

        return redirect('/')->with([
            'flash_message' => "Welcome to ContentLaunch! You're now part of the {$guestInvite->account->name} account!",
            'flash_message_type' => 'success',
            'flash_message_important' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param InvitedAccountRequest $request
     * @param AccountInvite $guestInvite
     * @return \Illuminate\Http\Response
     */
    public function store(InvitedAccountRequest $request, AccountInvite $guestInvite)
    {

         $account = $guestInvite->account;

         $user = $this->createInvitedGuest($guestInvite, $request);

         $this->createNewUserSession($user);
         if($guestInvite->getAttribute("inviteable_type") == "App\Content"){
            $content_id = $guestInvite->getAttribute("inviteable_id");

            return redirect('/review/'.$content_id)->with([
                'flash_message' => "Welcome to ContentLaunch! You're now part of the {$guestInvite->account->name} account!",
                'flash_message_type' => 'success',
                'flash_message_important' => true
            ]);

         }else{
            return redirect('/content/')->with([
                'flash_message' => "Welcome to ContentLaunch! You're now part of the {$guestInvite->account->name} account!",
                'flash_message_type' => 'success',
                'flash_message_important' => true
            ]);
         }

    }

    protected function createNewUserSession($user)
    {
        Auth::logout();
        Session::flush();
        Auth::login($user);
    }

    private function createInvitedGuest(AccountInvite $invite, $request)
    {
        return $invite->createGuest([
            'name' => $request->name,
            'password' => $request->password,
            'email' => $request->email,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
