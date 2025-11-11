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

    /**
     * Store a newly created genre.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name',
            'description' => 'nullable|string|max:2000',
        ]);

        \App\Models\Genre::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return back()->with('success', 'Genre created successfully');
    }

    /**
     * Remove the specified genre.
     */
    public function destroy(\App\Models\Genre $genre)
    {
        // Prevent deletion if there are books
        if ($genre->books()->count() > 0) {
            return back()->with('error', 'Cannot delete a genre that has books');
        }

        $genre->delete();
        return back()->with('success', 'Genre deleted successfully');
    }

    /**
     * Update the specified genre.
     */
    public function update(Request $request, \App\Models\Genre $genre)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,' . $genre->id,
            'description' => 'nullable|string|max:2000',
        ]);

        $genre->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return back()->with('success', 'Genre updated successfully');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}