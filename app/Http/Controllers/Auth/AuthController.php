<?php

namespace App\Http\Controllers\Auth;

use \Illuminate\Http\Request;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Session;
use Auth;
use Storage;
use Mail;

use App\Http\Controllers\Controller;
use App\User;
use App\Account;
use App\Helpers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (isset($_POST['redirect_url'])) {
            $this->redirectTo = $_POST['redirect_url'];
        }
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    public function logout()
    {
        Auth::guard($this->getGuard())->logout();
        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => [
                'regex:/^([\w-.]+@(?!(gmail|outlook|yahoo|live|msn|hotmail)\.com$)([\w-]+.)+[\w-]{2,4})?$/',
                "required",
                "email",
                "max:255",
                "unique:users",
            ],
            'password' => 'required|min:8',
            'company_name' => 'required',
            'account_type' => 'required',
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->saveFileUrlToSession($request->file('avatar'));
            $this->throwValidationException($request, $validator);
        }

        Auth::guard($this->getGuard())->login($this->create($request->all()));

        return redirect($this->redirectPath());
    }

    private function saveFileUrlToSession($file)
    {
        if ($file) {
            $tempUrl = Helpers::handleTmpUpload($file);
            Session::put('avatar_temp_url', $tempUrl);
        }
    }

    protected  function authenticated(Request $request)
    {
        Account::selectedAccount()->ensureAccountHasSubscription();

        return redirect()->intended( $this->redirectPath() );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */

    protected function create(array $data)
    {
        $account = $this->createAccount($data);
        $user = $this->createUser($data);

        $account->users()->attach($user);

        $this->handleProfilePicture($user, $data);
        $this->sendSignupEmail($user);

        return $user;
    }

    private function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    private function createAccount(array $data)
    {
        $account = Account::create([
            'name' => $data['company_name'],
            'account_type_id' => $data['account_type'],
        ]);

        $account->startTrial();

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

    private function handleProfilePicture($user, $data)
    {
        if (collect($data)->has('avatar')) {

            $user->profile_image = Helpers::handleProfilePicture($user, $data['avatar']);

        } elseif ($fileUrl = Session::get('avatar_temp_url')) {

            $user->profile_image = $this->movedProfileImage($user->id, $fileUrl);
            Session::forget('avatar_temp_url');

        }

        $user->save();
    }

    private function movedProfileImage($userId, $fileUrl)
    {
        $newS3Path = Helpers::userImagesFolder($userId);
        $profileS3Path = Helpers::moveFileToFolder($fileUrl, $newS3Path);

        return Storage::url($profileS3Path);
    }
}
