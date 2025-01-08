<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Check if user exists
        $user = User::where('username', $username)
                        ->where('deleted_at', NULL)
                        ->first();
        if(!$user){
            return redirect()
                ->back()
                ->withInput()
                ->with('loginError', 'Username ou password incorreto.');
        }

        // chack if password is correct
        if(!password_verify($password, $user->password)){
            return redirect()
                ->back()
                ->withInput()
                ->with('loginError', 'Username ou password incorreto.');
        }

        // update last login
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        // login user
        session([
            'user' => [
                'id' => $user->id,
                'username' => $user->username
            ]
        ]);

        echo 'LOGIM COM SUCESSO!';
    }

    public function logout()
    {
        // logout from the application

        session()->forget('user');
        return redirect()->to('/login');
    }

}
