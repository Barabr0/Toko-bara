<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout' );
        $this->middleware('auth')->only('logout');
    }
    /**
     *@return string
     */
    protected function redirectTo(): string
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return route('admin.dashboard');
        }
        return route('home');
    }
    /**
     * @param Illuminate\Http\Request $request
     */
    protected function validateLogin($request)
    {
        $request->validate([
            $this->username() => 'required|string|email',
            'password' => 'required|string|min:6',
        ],[
            'email.required' => 'email wajib isi',
            'email.email' => 'Format email tidak vali (harus ada @).',
            'password.required' => 'password wajib isi',
            'password.min' => 'password minimal 6 karakter/nomor.',
        ]);
    }
}
