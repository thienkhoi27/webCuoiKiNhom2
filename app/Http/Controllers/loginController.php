<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class loginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $username)->first();

        if ($user && Hash::check($password, $user->password)) {
            session()->put('username', $user->username);
            session()->put('profilePicture', $user->profilePicture);

            return redirect('/')->with('success', 'Logged in successfully!');
        } else {
            return back()->with('error', 'Invalid username or password');
        }
    }
}
