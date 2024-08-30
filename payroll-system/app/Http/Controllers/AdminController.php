<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function showUpdatePage()
    {
        $admin = auth()->user();
        $users = User::where('adminID', $admin->id)
            ->select('id', 'firstName', 'lastName', 'middleName', 'nameExt', 'email')
            ->get();

        return Inertia::render('Admin/Update', [
            'users' => $users
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->adminID !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'nameExt' => 'nullable|string|max:50',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->adminID !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
