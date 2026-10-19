<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AuthenticationController extends Controller
{

    public function login():View
    {
        return view('auth.login');
    }

    public function authenticate():string{
        return "Hello world";
    }

}
