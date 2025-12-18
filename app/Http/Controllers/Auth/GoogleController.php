<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * @return Illuminate\Http\RedirectResponse
     */
    public function redirect() {
        return Socialite::driver('google')
        ->scopes(['email','profile'])
        ->redirect();
    }
    /**
     * @return Illuminate\Http\RedirectResponse
     */
    public function callback()
    {
        if (request()->has('error')) {
            $error = request('error');
            if ($error === 'access_denied') {
                return redirect()
                ->route('login')
                ->with('info','Login dengan akun google di batalkan.');
            }
            return redirect()
            ->route('login')
            ->with('error','Terjadi Kesalahan.' . $error);
        }
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = $this->findOrCreateUser($googleUser);
            Auth::login($user, remember: true);
            session()->regenerate();
            return redirect()
            ->intended(route('home'))
            ->with('success', 'berhasil login dengan akun google!');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            logger()->error('Google API Error:' . $e->getMesagge());
            return redirect()
            ->route('login')
            ->with('error', 'gagal login silahkan Coba lagi');

        }
    }
    /**
     * @param \Laravel\Socialite\Contracts\User
     * @return \App\Models\User
     */
    protected function findOrCreateUser($googleUser) : User {
        $user = User::where('google_id',$googleUser->getId())->first();
        if ($user) {
            if ($user->avatar !== $googleUser->getAvatar()) {
                $user->update(['avatar'=>$googleUser->getAvatar()]);
            }
            return $user;
        }
          $user = User::where('email', $googleUser->getEmail())->first();
           if ($user) {
            $user->update([
                'google_id'=> $googleUser->getId(),
                'avatar'=> $googleUser->getAvatar()??$user->avatar,
                     'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
            return $user;
        }
         return User::create([
            'name' => $googleUser->getName(),

            'email' => $googleUser->getEmail(),

            'google_id' => $googleUser->getId(),

            'avatar' => $googleUser->getAvatar(),

            'email_verified_at' => now(),

            'password' => Hash::make(Str::random(24)),

            'role' => 'customer',

        ]);
    }
}
