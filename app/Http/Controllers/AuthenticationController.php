<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthenticationController extends Controller
{

    public function login():View
    {
        return view('auth.login');
    }

    public function authenticate(Request $request):string{

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:255',
        ]);

        if(auth()->attempt($request->only('email','password'))){
            return redirect()->route('home')->with('success', 'Welcome back!');
        }
        return back()->withErrors(['email' => 'Invalid credentials']);

    }

}
