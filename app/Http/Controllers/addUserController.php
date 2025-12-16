<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class addUserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
            'reenterPassword' => ['required', 'string'],
        ]);

        if (User::where('username', $request->username)->exists()) {
            return back()->with('error', 'User already exists');
        }

        if ($request->password != $request->reenterPassword) {
            return back()->with('error', 'Passwords do not match');
        }

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'profilePicture' => 'images/profile-picture/default.svg',
        ]);

        return redirect('/login')->with('success', 'User created successfully!');
    }
}
