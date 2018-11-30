<?php

namespace App\Http\Controllers\Auth;

use App\Customer;
use App\Stan;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'saldo' => 'nullable|numeric|digits_between:3,10',
            'foto' => 'required|image|mimes:jpeg,jpg,png',
            'alamat' => 'required|string',
            'nohp' => 'required|numeric|digits_between:9,15',
            'email' => 'required|email|string|max:255|unique:customer',
        ]);

        $data = $request->all();

        DB::beginTransaction();
        try {
            $data['role'] = 3;
            $data['api_token'] = bcrypt(date('Ymd').$data['username']);
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            $data['foto'] = uniqid($user->id) . '.' . $request->foto->getClientOriginalExtension();
            $user->customer()->create($data);
            reziseImage($request->foto, 'customer', $data['foto']);

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Selamat anda berhasil mendaftar, Silahkan Log In'
            ]);
        }catch (QueryException $exception){
            DB::rollBack();

            return response()->json([
                'status' => 2,
                'exeption' => $exception
            ],500);
        }

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
