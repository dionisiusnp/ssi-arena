<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $user = Auth::user();
        
        // is_active || is_member || is_lecturer
        // true || null || true = admin
        // true || true || true = pengajar
        // true || true || false = member
        // true || false || false = bootcamp

        if ($user->is_active == 0) {
            Auth::logout();
            return redirect()->back()->withErrors([
                'email' => 'Akun ini tidak memiliki akses, silahkan hubungi admin.',
            ]);
        }

        $request->session()->regenerate();

        if ($user->is_lecturer == 1) {
            return redirect()->intended(route('admin-panel', absolute: false));
        } else {
            return redirect()->intended(route('member.schedule', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
