<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'middleName' => 'nullable|string|max:255',
                'nameExt' => 'nullable|string|max:50',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Get the logged-in admin's ID
            $adminID = Auth::guard('admin')->id();

            if (!$adminID) {
                throw new \Exception('Admin not authenticated');
            }

            $user = User::create([
                'firstName' => $validatedData['firstName'],
                'lastName' => $validatedData['lastName'],
                'middleName' => $validatedData['middleName'],
                'nameExt' => $validatedData['nameExt'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'adminID' => $adminID,
            ]);

            Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email, 'admin_id' => $adminID]);

            event(new Registered($user));

            return redirect()->back()->with('success', 'User registered successfully');

        } catch (\Exception $e) {
            Log::error('User registration failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}
