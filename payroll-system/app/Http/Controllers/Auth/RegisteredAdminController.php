<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredAdminController extends Controller
{
    /**
     * Display the registration view for admins.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/AdminRegister');
    }

    /**
     * Handle an incoming registration request for admins.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'nameExt' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin = Admin::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'middleName' => $request->middleName,
            'nameExt' => $request->nameExt,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_number' => 2,
            'otp' => null,
            'otp_expires_at' => null,
            'otp_verified_at' => null,
        ]);

        event(new Registered($admin));

        return redirect()->route('admin.login');
    }
}
