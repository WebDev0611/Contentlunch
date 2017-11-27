<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Controllers\Auth\AuthController;
use App\SubscriptionType;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;


class QebotLoginController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    public function create(Request $request){

        try{
            $this->validate($request, [
                "token" => 'required|exists:api_users',
                "name" => 'required',
                "email" => 'required|email|unique:users',
                "profile_image" => 'required|url',
                "company_name" => 'required',
                "account_type" => 'required'
            ]);
        }catch (ValidationException $exception){
            return response()->json([
                "error" => $exception->getMessage(),
                "messages" => $exception->validator->messages()
            ]);
        }

        $data = [
            'token' => $request->token,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->token,
            'password_confirmation' => $request->token,
            'profile_image' => $request->profile_image,
            'company_name' => $request->company_name,
            'account_type' => $request->account_type,
            'qebot_user' => true,
            'is_guest' => false,
        ];

        Auth::guard($this->getGuard())->login($this->register($data));

        return response()->json([
            "user" => $this->trimUser(Auth::user()),
            "session_id" => Crypt::encrypt(session()->getId())
        ]);
    }

    public function login(Request $request) {

        try{
            $this->validate($request, [
                "token" => 'required|exists:api_users',
                "id" => 'required|exists:users'
            ]);
        }catch (ValidationException $exception){
            return response()->json([
                "error" => $exception->getMessage(),
                "messages" => $exception->validator->messages()
            ]);
        }

        if (!$this->createNewUserSession($request->get('id'), $request->get('token'))){
            return response()->json([
                "error" => "Unable to authenticate user."
            ]);
        }

        return response()->json([
            "user" => $this->trimUser(Auth::user()),
            "session_id" => Crypt::encrypt(session()->getId())
        ]);
    }

    protected function trimUser($user){
        return [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "profile_image" => $user->profile_image,
        ];
    }

    protected function createNewUserSession($id, $password)
    {
        Auth::logout();
        Session::flush();

        //Auth::loginUsingId($id);
        return Auth::attempt(['id' => $id, 'password' => $password]);
    }


    protected function register(array $data)
    {
        $account = $this->createAccount($data);
        $user = $this->createUser($data);

        $attributes = [ 'expiration_date' => Carbon::now()->addDays(30) ];
        $trialPlan = SubscriptionType::findBySlug('pro-monthly');

        $account->users()->attach($user);
        $account->subscribe($trialPlan, $attributes, false);

        $this->sendSignupEmail($user);

        return $user;
    }

    private function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'qebot_user' => $data['qebot_user'],
            'profile_image' => $data['profile_image'],
        ]);
    }

    private function createAccount(array $data)
    {
        $account = Account::create([
            'name' => $data['company_name'],
            'account_type_id' => $data['account_type'],
        ]);

        return $account;
    }

    private function sendSignupEmail(User $user)
    {
        Mail::send('emails.signup', compact('user'), function($message) use ($user) {
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                ->to($user->email)
                ->subject('Welcome to Content Launch');
        });
    }

}
