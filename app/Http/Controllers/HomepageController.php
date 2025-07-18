<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class HomepageController extends Controller
{
    public function index()
    {
        // return view('auth.register', [
        //     'ModulesInstalled' => $ModulesInstalled,
        //     'ModulesEnabled' => $ModulesEnabled,
        // ]);
        return view('home.home');
         // returns resources/views/your-view-name.blade.php
    }
}
