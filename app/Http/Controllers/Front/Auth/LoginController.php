<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/feeds';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request , [
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);
        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (Auth::guard()->attempt($credentials, $request->has('remember')))
        {
            return redirect()->route('front.feeds.index');
        }
        session()->flash('alert', ['type' => 'error', 'message' => 'Incorrect Email Or Password']);
        return redirect()->back();
    }

    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }


    public function handleProviderCallback()
    {
        $facebookUser = Socialite::driver('facebook')->user();
        $lastLogin = UserLogin::where(['external_user_id' => $facebookUser->id, 'type' => 'facebook'])->first();
        if (isset($lastLogin) && $lastLogin->hasUser()) {
            Auth::login($lastLogin->user);
            return redirect()->route('front.feeds.index');
        } else {
            $isEmailExist = User::searchByEmail($facebookUser->email)->get();
            if ($isEmailExist->count() == 0) {
                $user = User::create([
                    'f_name' => sub_words($facebookUser->name, 1, 0),
                    'l_name' => sub_words($facebookUser->name, 1, 1),
                    'email' => $facebookUser->email,
                    'password' => bcrypt(rand(100000, 900000)),
                ]);
                $user->saveFacebookAvatar($facebookUser->avatar_original);
                $user->createFacebookLogin($facebookUser);
                Auth::login($user);
                return redirect()->route('front.feeds.index');
            }
            session()->flash('error', ['message' => 'Email Already Exist']);
            return redirect()->route('front.home');
        }
    }
}
