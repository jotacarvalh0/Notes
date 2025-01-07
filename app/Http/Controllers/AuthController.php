<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    
    public function login()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        // for validation
        $request->validate(
            // rules
            [
                'text_username' => 'required | email',
                'text_password' => 'required | min:6 | max:16'
            ],
            //error messages
            [
                'text_username.required' => 'Username is required',
                'text_username.email' => 'Username must be a valid email.',

                'text_password.required' => 'Password is required',
                'text_password.min' => 'Password must be between 6 and 16 characters long.',
                'text_password.max' => 'Password must be between 6 and 16 characters long.',
            ]
        );

        // get user input
        $username = $request->input('text_username');
        $password = $request->input('text_password');

        echo 'OK!';
    }

    public function logout()
    {
        echo 'logout';
    }

}
