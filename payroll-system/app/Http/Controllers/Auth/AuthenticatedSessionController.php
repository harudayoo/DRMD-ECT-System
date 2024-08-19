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
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $this->updateLoginInfo($user);
        } elseif (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();
            Auth::login($user); // Log in the admin as a regular user
            // We don't update login info for admins
        } else {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('otp.show');
    }

    private function updateLoginInfo($user)
    {
        if (method_exists($user, 'updateLoginInfo')) {
            $now = Carbon::now();
            if ($this->last_login_reset === null || $now->diffInDays($this->last_login_reset) >= 30) {
                $this->loginNum = 1;
                $this->last_login_reset = $now;
            } else {
                $this->loginNum++;
            }
            $this->save();
        }
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

}
