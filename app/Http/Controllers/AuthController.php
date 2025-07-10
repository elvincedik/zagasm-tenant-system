<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Organization;

class AuthController extends BaseController
{

    //--------------- Function Login ----------------\\

    public function getAccessToken(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $userStatus = Auth::User()->statut;
            if ($userStatus === 0) {
                return response()->json([
                    'message' => 'This user not active',
                    'status' => 'NotActive',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Incorrect Login',
                'status' => false,
            ]);
        }

        $user = auth()->user();
        $tokenResult = $user->createToken('Access Token');
        $token = $tokenResult->token;
        $this->setCookie('Stocky_token', $tokenResult->accessToken);

        return response()->json([
            'Stocky_token' => $tokenResult->accessToken,
            'username' => Auth::User()->username,
            'status' => true,
        ]);
    }

    //--------------- Function Logout ----------------\\

    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user()->token();
            $user->revoke();
            $this->destroyCookie('Stocky_token');
            return response()->json('success');
        }
    }


    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'organization_name' => 'required|string|max:255',
    //         'firstname' => 'required|string|max:255',
    //         'lastname' => 'required|string|max:255',
    //         'username' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6|confirmed',
    //         'phone' => 'nullable|string|max:20',
    //     ]);

    //     // Create organization
    //     $organization = Organization::create([
    //         'name' => $request->organization_name,
    //     ]);

    //     // Create user
    //     $user = User::create([
    //         'firstname' => $request->firstname,
    //         'lastname' => $request->lastname,
    //         'username' => $request->username,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'phone' => $request->phone,
    //         'avatar' => 'no_avatar.png',      // default
    //         'role_id' => 1,                   // default admin role
    //         'statut' => 1,                    // active
    //         'is_all_warehouses' => 1,         // full access
    //         'organization_id' => $organization->id, // link to org
    //     ]);

    //     // Issue token
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'access_token' => $token,
    //         'user' => $user,
    //         'organization' => $organization,
    //     ]);
    // }

    public function register(Request $request)
    {
        $request->validate([
            'organization_name' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6', // ✅ updated here
            'phone' => 'nullable|string|max:20',
        ]);

        // Create organization
        $organization = Organization::create([
            'name' => $request->organization_name,
        ]);

        // Create user
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'avatar' => 'no_avatar.png',
            'role_id' => 1,
            'statut' => 1,
            'is_all_warehouses' => 1,
            'organization_id' => $organization->id,
        ]);

        // Issue token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user,
            'organization' => $organization,
        ]);
    }


    // public function register(Request $request) {
    //     dd("hi");
    // }
}