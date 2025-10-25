<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $users = User::all();
        return view('admin.dashboard', compact('users'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'User role updated successfully');
    }

    public function books()
    {
        $books = \App\Models\Books::with(['user', 'genre'])->latest()->paginate(10);
        return view('admin.books', compact('books'));
    }

    public function genres()
    {
        $genres = \App\Models\Genre::withCount('books')->latest()->paginate(10);
        return view('admin.genres', compact('genres'));
    }

    public function settings()
    {
        return view('admin.settings');
    }
}