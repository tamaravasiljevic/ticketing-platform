<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
            $socialUser = Socialite::driver('github')->user();

            // Check if there's an existing social account
            $account = SocialAccount::where('provider_name', 'github')
                ->where('provider_id', $socialUser->getId())
                ->first();

            if ($account) {
                // Login the user linked with this social account
                $user = $account->user;
                Auth::login($user);

                return redirect()->route('home');
            } else {
                // Check if the user exists based on their email
                $user = User::where('email', $socialUser->getEmail())->first();

                if (!$user) {
                    // Create the user if they don't exist
                    $user = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'password' => bcrypt(str_random(16)), // Generate a random password
                    ]);
                }

                // Link the social account
                $user->socialAccounts()->create([
                    'provider_name' => 'github',
                    'provider_id' => $socialUser->getId(),
                ]);

                Auth::login($user);

                return redirect()->route('home');
            }
        } catch (\Exception $e) {
            Log::error('Github Login Error: ' . $e->getMessage());;
            return redirect()->route('login')->withErrors(['error' => 'Unable to login using GitHub. Please try again.']
            );
        }
    }
}
