<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

public function login_check(Request $request)
{
    $user = User::where('contact', $request->contact)->first();

    if ($user && $user->status === 'active') {
        $dbPassword = $user->password;
        $inputPassword = $request->password;

        $isHashed = substr($dbPassword, 0, 4) === '$2y$'; // bcrypt passwords always start with $2y$

        $validPassword = $isHashed 
            ? Hash::check($inputPassword, $dbPassword)   // check against hash
            : $dbPassword === $inputPassword;            // plain text compare

        if ($validPassword) {
            // Optional: auto-hash plain text passwords after successful login
            if (!$isHashed) {
                $user->password = Hash::make($inputPassword);
                $user->save();
            }

            Auth::guard('web')->login($user);

            Cookie::queue(cookie('cook_auth_admin', encrypt($user->id), 60 * 24 * 30));

            Log::info('User logged in: ' . auth('web')->user()->name);

            return redirect()->route('admin.dashboard.index')->with('success', 'Login successful');
        }
    }

    $reason = !$user ? 'User not found' : ($user->status !== 'active' ? 'Account inactive' : 'Invalid password');
    Log::warning("Failed login attempt for contact: {$request->contact} - {$reason}");

    return redirect()->back()->with('alert', 'Invalid credentials or account inactive!');
}


    public function logout()
    {
        Auth::guard('web')->logout();
        // ❌ Clear custom auth cookie
        Cookie::queue(Cookie::forget('cook_auth_admin'));

        return redirect()->route('admin.auth.login');
    }


    public function forgotpass()
    {
        return view('admin.auth.forgot_pass');
    }
    public function otp()
    {
        return view('admin.auth.otp');
    }
    public function changepass()
    {
        return view('admin.auth.change_pass');
    }
}
