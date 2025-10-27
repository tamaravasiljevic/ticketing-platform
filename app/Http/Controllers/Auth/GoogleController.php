<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            $user = Socialite::driver('google')->user();

            // Logika za prijavljivanje ili kreiranje korisnika
            $findUser = User::where('email', $user->email)->first();

            if ($findUser) {
                Auth::login($findUser);

                return redirect()->intended('/dashboard'); // ili bilo koja stranica
            } else {
                // Ako korisnik ne postoji, kreiramo ga
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => bcrypt('random_password'), // Preporučeno je generisanje nasumične lozinke
                ]);

                Auth::login($newUser);

                return redirect()->intended('/dashboard'); // ili druga stranica
            }
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['error' => 'Nešto je pošlo po zlu!']);
        }
    }
}
