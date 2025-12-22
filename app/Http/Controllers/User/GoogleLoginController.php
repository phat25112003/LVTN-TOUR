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

            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Create a new user
                $user = User::create([
                    'hoTen'      => $googleUser->name,
                    'email'      => $googleUser->email,
                    'google_id'  => $googleUser->id,
                    'avatar'     => $googleUser->avatar,   // ← Lưu avatar Google
                    'matKhau'    => bcrypt(uniqid()),
                ]);
            } else {
                // Nếu user login Google LẦN ĐẦU → cập nhật google_id + avatar
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                }

                // Nếu user chưa từng upload avatar → dùng avatar Google
                if (!$user->avatar || !file_exists(storage_path('app/public/public/avatar-users/' . $user->avatar))) {
                    $user->avatar = $googleUser->avatar;
                }

                $user->save();
            }

            Auth::login($user);
            return redirect()->route('home')->with('success', 'Login successful!');
        } catch (Exception $e) {
            return redirect()->route('user.login')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

}
