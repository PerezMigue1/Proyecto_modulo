<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    /**
     * Redirect the user to the Facebook authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     */
    public function callback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::where('email', $facebookUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $facebookUser->getName() ?: ($facebookUser->user["name"] ?? 'Usuario Facebook'),
                    'email' => $facebookUser->getEmail(),
                    'password' => bcrypt(str()->random(16)),
                    'facebook_id' => $facebookUser->getId(),
                    'email_verified_at' => now(),
                ]);
            } else if (!$user->facebook_id) {
                $user->facebook_id = $facebookUser->getId();
                $user->save();
            }

            Auth::login($user, true);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Error al autenticar con Facebook. Intenta de nuevo.']);
        }
    }
}


