<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Check if the social account exists
            $socialAccount = SocialAccount::where('provider_name', 'google')
                ->where('provider_id', $googleUser->getId())
                ->first();

            if ($socialAccount) {
                // If the social account exists, log in the associated user
                Auth::login($socialAccount->user);
            } else {
                // Find or create the user in the users table
                $user = User::firstOrCreate(
                    [
                        'email' => $googleUser->getEmail()
                    ],
                    [
                        'name' => $googleUser->getName(),
                    ]
                );

                // Create a new social account for this user
                $user->socialAccounts()->create([
                    'provider_name' => 'google',
                    'provider_id' => $googleUser->getId(),
                ]);

                Auth::login($user);
            }

            // Redirect to the dashboard
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            // Log error and redirect back with a message
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['message' => 'Failed to login with Google.']);
        }
    }
}
