<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NguoiDung as User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleLoginCOntroller extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if a user with this email exists
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Create a new user if not found
                $user = User::create([
                    'hoTen' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'matKhau' => bcrypt(uniqid()), // Random hashed password
                ]);
            }

            // Log in the user
            Auth::login($user);
            return redirect()->route('home')->with('success', 'Login successful!');
        } catch (Exception $e) {
            return redirect()->route('user.login')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
