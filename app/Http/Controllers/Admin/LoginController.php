<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/dashboard';
    protected $loginView = 'admin.layouts.login';

    public function authenticated(Request $request, User $user)
    {
        if (!$user->isAdmin()) {
            Auth::logout();

            return redirect()->route('admin.login.show')->with([
                'flash_message' => 'User not authorized.',
                'flash_message_type' => 'danger',
                'flash_message_important' => true,
            ]);
        }

        return redirect($this->redirectTo);
    }
}
