<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\News;
use App\Models\Port;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return redirect()->route('dashboard');
    }

    public function users()
    {
        return view('admin.users.index', ['users' => User::latest()->paginate(20)]);
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate(['peran' => ['required', 'in:admin,user']]);
        abort_if($user->is(auth()->user()) && $validated['peran'] !== 'admin', 422, 'Admin tidak dapat menurunkan perannya sendiri.');
        $user->update($validated);
        return back()->with('success', 'Peran pengguna berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        abort_if($user->is(auth()->user()), 422, 'Admin tidak dapat menghapus akunnya sendiri.');
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
