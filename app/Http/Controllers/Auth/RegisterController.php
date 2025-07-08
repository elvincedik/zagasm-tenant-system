<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use \Nwidart\Modules\Facades\Module;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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

    

    public function showRegisterForm()
    {
        $allModules = \Nwidart\Modules\Facades\Module::all();
        $allEnabledModules = \Nwidart\Modules\Facades\Module::allEnabled();

        $ModulesInstalled = [];
        $ModulesEnabled = [];

        foreach ($allModules as $key => $modules_name) {
            $ModulesInstalled[] = $key;
        }

        foreach ($allEnabledModules as $key => $modules_name) {
            $ModulesEnabled[] = $key;
        }

        return view('auth.register', [
            'ModulesInstalled' => $ModulesInstalled,
            'ModulesEnabled' => $ModulesEnabled,
        ]);

    }

}