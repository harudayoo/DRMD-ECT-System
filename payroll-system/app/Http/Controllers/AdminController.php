<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function verifyPassword(Request $request)
    {
        $admin = auth()->user();
        $verified = Hash::check($request->password, $admin->password);

        if ($verified) {
            $users = User::where('adminID', $admin->id)
                ->select('id', 'firstName', 'lastName', 'middleName', 'nameExt', 'email')
                ->get();
            return Inertia::render('Admin/Update', [
                'verified' => true,
                'users' => $users
            ]);
        } else {
            return Inertia::render('Admin/Update', [
                'verified' => false
            ]);
        }
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->adminID !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }
        $user->delete();
        return redirect()->route('admin.update')->with('success', 'User deleted successfully');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->adminID !== auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Unauthorized action');
        }
        return Inertia::render('Admin/EditUser', ['user' => $user]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->adminID !== auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Unauthorized action');
        }
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'nameExt' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('admin.update')->with('success', 'User updated successfully');
    }
}
