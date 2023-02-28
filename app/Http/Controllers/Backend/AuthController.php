<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Admins;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function login(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function storeLogin(LoginRequest $request): RedirectResponse
    {
        $request->authenticate('admin');

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::DASHBOARD);
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admins')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return to_route('admin.auth.login');
    }

    /**
     * Display the registration view.
     */
    public function register(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeRegister(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . Admins::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Admins::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect(RouteServiceProvider::DASHBOARD);
    }
}
