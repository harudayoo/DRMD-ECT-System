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
            $validatedData = $this->validateUser($request);

            $adminID = $this->getAuthenticatedAdminID();

            $user = $this->createUser($validatedData, $adminID);

            event(new Registered($user));

            return redirect()->back()->with('success', 'User registered successfully');
        } catch (\Exception $e) {
            Log::error('User registration failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function storeRequest(Request $request)
    {
        try {
            $validatedData = $this->validateUser($request);

            $adminID = $this->getAuthenticatedAdminID();

            $user = $this->createUser($validatedData, $adminID);

            event(new Registered($user));

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('User registration failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 422);
        }
    }

    private function validateUser(Request $request): array
    {
        return $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'nameExt' => 'nullable|string|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    }

    private function getAuthenticatedAdminID(): int
    {
        $adminID = Auth::guard('admin')->id();

        if (!$adminID) {
            throw new \Exception('Admin not authenticated');
        }

        return $adminID;
    }

    private function createUser(array $data, int $adminID): User
    {
        $user = User::create([
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'middleName' => $data['middleName'] ?? null,
            'nameExt' => $data['nameExt'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'adminID' => $adminID,
            'role_number' => 1, // Add role_number field with value 1
        ]);

        Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email, 'admin_id' => $adminID]);

        return $user;
    }
}
