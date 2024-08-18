<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


class AdminAuthenticatedSessionController extends Controller
{
    /**
     * Display the admin login view.
     */
    public function create()
    {
        return Inertia::render('Admin/AdminLogin', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();

            // Manually set the user ID in the session
            $admin = Auth::guard('admin')->user();
            $request->session()->put('admin_id', $admin->id);

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    /**
     * Destroy an authenticated admin session.
     */
    public function destroy(Request $request)
    {
        \Log::info('Admin logout attempt', [
            'user' => Auth::guard('admin')->user(),
            'is_authenticated' => Auth::guard('admin')->check()
        ]);

        if (!Auth::guard('admin')->check()) {
            \Log::warning('Logout attempted without authentication');
            return response()->json(['message' => 'Not authenticated'], 401);
        }

        // Get the admin ID before logging out
        $adminId = Auth::guard('admin')->id();

        Auth::guard('admin')->logout();

        // Clear the admin_id from the session manually
        $request->session()->forget('admin_id');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Log::info('Admin logged out successfully', ['user_id' => $adminId]);

        return response()->json(['message' => 'Logged out successfully']);
    }




}
