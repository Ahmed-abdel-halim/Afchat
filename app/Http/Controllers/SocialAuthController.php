<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private function supportedProvider(string $provider): string
    {
        if (!in_array($provider, ['google', 'facebook'], true)) {
            abort(404, 'Provider not supported');
        }
        return $provider;
    }

    public function redirect(string $provider)
    {
        $provider = $this->supportedProvider($provider);

        return Socialite::driver($provider)
            ->stateless()
            ->redirect();
    }

    public function callback(string $provider)
    {
        $provider = $this->supportedProvider($provider);

        $socialUser = Socialite::driver($provider)->stateless()->user();

        $email = $socialUser->getEmail();
        $providerId = (string) $socialUser->getId();

        $user = null;

        if ($email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            $user = User::where('provider', $provider)
                ->where('provider_id', $providerId)
                ->first();
        }

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName() ?: ($socialUser->getNickname() ?: 'User'),
                'email' => $email ?: (Str::uuid().'@'.$provider.'.local'),
                'password' => bcrypt(Str::random(32)),
                'provider' => $provider,
                'provider_id' => $providerId,
                'avatar' => $socialUser->getAvatar(),
            ]);
        } else {
            $user->update([
                'provider' => $user->provider ?: $provider,
                'provider_id' => $user->provider_id ?: $providerId,
                'avatar' => $socialUser->getAvatar() ?: $user->avatar,
                'name' => $socialUser->getName() ?: $user->name,
            ]);
        }

        $token = $user->createToken('afshat')->plainTextToken;

        $frontend = config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000'));
        return redirect()->away($frontend . '/auth/callback?token=' . urlencode($token));
    }
}

