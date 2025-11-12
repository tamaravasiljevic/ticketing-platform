<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialUser;

class SocialLoginController extends Controller
{
    // Redirect to GitHub for login
    public function redirectToGitHub()
    {
        return Socialite::driver('github')->redirect();
    }

    // Handle the GitHub callback
    public function handleGitHubCallback()
    {
        try {
            $socialUser = Socialite::driver('github')->stateless()->user();
            $this->handleLogin('github', $socialUser);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            \Log::error('Github Login Error: ' . $e->getMessage());;
            return redirect()->route('login')->withErrors(['error' => 'Unable to login using GitHub. Please try again.']
            );
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
            $this->handleLogin( 'facebook', $facebookUser);
            return redirect()->route('dashboard')->withFragment('');
        } catch (\Exception $e) {
            // Log error and redirect back with a message
            \Log::error('Facebook Login Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['message' => 'Failed to login with Facebook.']);
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $this->handleLogin('google', $googleUser);
            // Redirect to the dashboard
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            // Log error and redirect back with a message
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['message' => 'Failed to login with Google.']);
        }
    }

    private function handleLogin(string $social, ?SocialUser $socialUser)
    {
        // Check if there's an existing social account
        $account = SocialAccount::where('provider_name', $social)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($account) {
            // Login the user linked with this social account
            $user = $account->user;
            Auth::login($user);
        } else {
            // Check if the user exists based on their email
            $user = User::firstWhere('email', $socialUser->getEmail());

            if (!$user) {
                // Create the user if they don't exist
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail()
                ]);
            }

            // Link the social account
            $user->socialAccounts()->create([
                'provider_name' => $social,
                'provider_id' => $socialUser->getId(),
            ]);
        }
    }
}
