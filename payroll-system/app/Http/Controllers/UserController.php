<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function dashboard()
    {
        // Get the currently logged in admin
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            // Handle the case where no admin is logged in
            return redirect()->route('admin.login')->with('error', 'Please log in as an admin.');
        }

        // Fetch users with the same adminID as the logged-in admin
        $users = User::where('adminID', $admin->id)
            ->select('name', 'loginNum')
            ->orderBy('loginNum', 'desc')
            ->take(10)
            ->get();

        // Debug: Log the number of users fetched
        \Log::info('Fetched ' . $users->count() . ' users for admin ID: ' . $admin->id);

        return Inertia::render('Admin/Dashboard', [
            'userData' => $users
        ]);
    }
}
