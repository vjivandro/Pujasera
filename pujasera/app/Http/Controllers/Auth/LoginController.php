<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();

        return redirect()->route('login');
    }

    public function logoutApi(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();

        return response()->json([
            'status' => 1,
            'message' => 'Logged Out'
        ]);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if (Auth::user()->role != 1) {
            $this->guard()->logout();
            $request->session()->invalidate();
        }

        Session::flash('success','Welcome '.Auth::user()->username);
        return $this->authenticated($request, $this->guard()->user())
            ?: response()->json([
                'status' => 1,
                'message' => 'Welcome '.Auth::user()->username,
                'redirect' => redirect()->intended($this->redirectPath())->getTargetUrl()
            ]);
    }

    protected function loginApi(Request $request)
    {
        $this->validateLogin($request);

        if($this->attemptLogin($request)){
            $this->clearLoginAttempts($request);

            $user = User::find(Auth::user()->id);
            $user->api_token = bcrypt(date('Ymd').$user->username.$user->id);
            $user->save();

            if($user->role == 2){
                $response = [
                    'message' => 'Welcome '.$user->stan->nama,
                    'user' => $user->getStan(),
                ];
            }elseif ($user->role == 3){
                $response = [
                    'message' => 'Welcome '.$user->customer->nama,
                    'user' => $user->getCustomer(),
                ];
            }


            return $this->authenticated($request, $this->guard()->user())
                ?: response()->json($response + [
                        'meta' => [
                            'token' => $user->api_token,
                            'role' => $user->role
                        ],
                        'status' => 1
                    ]);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);

    }

}
