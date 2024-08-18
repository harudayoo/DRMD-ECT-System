<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin; // Ensure this is the correct model
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return Inertia::render('Auth/AdminRegister'); // Adjust the view if necessary
    }

    /**
     * Handle an incoming registration request for admins.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins', // Use 'admins' table for admin registration
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the new admin
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_number' => 2,
        ]);

        // Fire the Registered event
        event(new Registered($admin));

        // Log the admin in
        //Auth::login($admin);

        // Redirect to the admin dashboard or a specific route
        return redirect()->route('admin.login'); // Adjust the route if necessary
    }
}
