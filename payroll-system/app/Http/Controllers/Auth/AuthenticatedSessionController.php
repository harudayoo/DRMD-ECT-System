<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the user login view.
     */
    public function create()
    {
        return Inertia::render('User/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $now = Carbon::now();

        // Check if last login reset was more than a month ago
        if ($user->last_login_reset === null || $now->diffInDays($user->last_login_reset) >= 30) {
            $user->loginNum = 1;
            $user->last_login_reset = $now;
        } else {
            $user->loginNum++;
        }

        $user->save();

        return redirect()->intended(route('user.dashboard'));
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
