<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userPermissions()
    {
        $users = \App\User::with('roles')->get(); // Fetch users with their roles
        return view('admin.user_permissions', compact('users'));
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = \App\User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'status' => 1,
            'records_authored' => 0,
        ]);
        $user->roles()->attach($request->role_id);

        return redirect()->route('admin.user_permissions')->with('success', 'User added successfully!');
    }

    public function removeUser($id)
    {
        $user = \App\User::findOrFail($id);

        // Detach all roles from the user (removes from role_user table)
        $user->roles()->detach();

        // Now delete the user
        $user->delete();

        return redirect()->route('admin.user_permissions')->with('success', 'User removed successfully!');
    }

    public function reactivateUser($id)
    {
        $user = \App\User::findOrFail($id);
        $user->status = 1;
        $user->save();

        return redirect()->route('admin.user_permissions')->with('success', 'User reactivated successfully!');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = \App\User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('admin.user_permissions')->with('success', 'Password reset successfully!');
    }
}
