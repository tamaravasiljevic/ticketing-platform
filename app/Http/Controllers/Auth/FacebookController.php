<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            // Check if the social account exists
            $socialAccount = SocialAccount::where('provider_name', 'facebook')
                ->where('provider_id', $facebookUser->id)
                ->first();

            if ($socialAccount) {
                // If the social account exists, log in the associated user
                Auth::login($socialAccount->user);
            } else {
                // Find or create the user in the users table
                $user = User::firstOrCreate(
                    [
                        'email' => $facebookUser->getEmail()
                    ],
                    [
                        'name' => $facebookUser->getName(),
                        'password' => bcrypt('default_password')
                    ] // Default password or ignore
                );

                // Create a new social account for this user
                $user->socialAccounts()->create([
                    'provider_name' => 'google',
                    'provider_id' => $facebookUser->id,
                ]);

                Auth::login($user);
            }

            // Redirect to the dashboard
            return redirect()->route('dashboard')->withFragment('');
        } catch (\Exception $e) {
            // Log error and redirect back with a message
            \Log::error('Facebook Login Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['message' => 'Failed to login with Facebook.']);
        }
    }
}
