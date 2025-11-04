<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\GoogleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google after authentication.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists
            $existingUser = GoogleUser::where('google_id', $googleUser->getId())->first();

            if ($existingUser) {
                // User exists, update their information
                $existingUser->update([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);

                $user = $existingUser;
            } else {
                // Create new user
                $user = GoogleUser::create([
                    'google_id' => $googleUser->getId(),
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);
            }

            // Login the user using the google guard
            Auth::guard('google')->login($user);

            return redirect()->route('google.dashboard');

        } catch (\Exception $e) {
            return redirect('/')->withErrors(['error' => 'Google authentication failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Logout the Google user.
     */
    public function logout()
    {
        Auth::guard('google')->logout();
        return redirect('/');
    }
}