<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard');

        \Log::info('Admin dashboard accessed');
    }

    public function dashboardChart()
    {
        \Log::info('Admin accessing dashboard', ['user' => auth()->user()]);
        // Get the currently logged in admin
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            // Handle the case where no admin is logged in
            return redirect()->route('admin.login')->with('error', 'Please log in as an admin.');
        }

        // Fetch users with the same adminID as the logged-in admin
        $users = User::where('adminID', $admin->id)
            ->select('firstName', 'middleName', 'lastName', 'nameExt', 'loginNum')
            ->orderBy('loginNum', 'desc')
            ->take(10)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => trim("{$user->firstName} {$user->middleName} {$user->lastName} {$user->nameExt}"),
                    'loginNum' => $user->loginNum
                ];
            });

        // Debug: Log the number of users fetched
        \Log::info('Fetched ' . $users->count() . ' users for admin ID: ' . $admin->id);

        return Inertia::render('Admin/Dashboard', [
            'userData' => $users
        ]);
    }
}
