<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Client;
use App\Models\Setting;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'organization_name' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);
    }

    protected function create(array $data)
    {
        // Create organization
        $organization = Organization::create([
            'name' => $data['organization_name'],
        ]);

        // Create user
        $user = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'avatar' => 'no_avatar.png',
            'role_id' => 1,
            'statut' => 1,
            'is_all_warehouses' => 1,
            'organization_id' => $organization->id,
        ]);

        $user->roles()->attach(1);

        // $client = Client::create([
        //     // 'id' => $user->id,
        //     'name' => $data['firstname'] . ' ' . $data['lastname'],
        //     'code' => $user->id,
        //     'email' => $data['email'],
        //     'country' => 'Nigeria',
        //     'city' => 'Unknown',
        //     'phone' => $data['phone'] ?? '0000000000',
        //     'adresse' => 'Unknown Address',
        //     'tax_number' => null,
        // ]);

        // // Create Settings for the new client
        // Setting::create([
        //     'client_id' => $client->id,
        //     'email' => $data['email'],
        //     'currency_id' => 1,
        //     'is_invoice_footer' => 0,
        //     'invoice_footer' => null,
        //     'warehouse_id' => null,
        //     'CompanyName' => $data['firstname'] . ' ' . $data['lastname'],
        //     'CompanyPhone' => $data['phone'] ?? '0000000000',
        //     'CompanyAdress' => 'Enter your address here',
        //     'footer' => 'Stocky - Ultimate Inventory With POS',
        //     'developed_by' => 'Stocky',
        //     'logo' => 'logo-default.png',
        //     'app_name' => 'Stocky | Ultimate Inventory With POS',
        //     'page_title_suffix' => 'Ultimate Inventory With POS',
        //     'favicon' => 'favicon.ico',
        // ]);

        return $user;
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