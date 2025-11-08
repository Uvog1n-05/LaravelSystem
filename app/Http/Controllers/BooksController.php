<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books; 
use App\Models\Genre;
class BooksController extends Controller
{
    public function index(Request $request) {
        $query = Books::with('genre');

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('author', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('genre', function($q) use ($searchTerm) {
                      $q->where('genre_name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // Apply genre filter
        if ($request->filled('genre')) {
            $query->where('genre_id', $request->genre);
        }

        // Apply availability filter
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('number_of_books', '>', 0);
            } elseif ($request->availability === 'unavailable') {
                $query->where('number_of_books', '=', 0);
            }
        }

        // Apply sort order
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSortFields = ['title', 'author', 'created_at'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Get books with pagination
        $books = $query->paginate(12)->withQueryString();
        
        // Get featured books only if not searching or filtering
        $featuredBooks = !$request->hasAny(['search', 'genre', 'availability']) 
            ? Books::with('genre')->orderBy('created_at', 'desc')->take(16)->get()
            : collect();

        // Get all genres with their books
        $genres = Genre::with(['books' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();

        return view('books.index', [
            'books' => $books,
            'featuredBooks' => $featuredBooks,
            'genres' => $genres
        ]);
    }

    public function show(Books $books) {

         $books ->load('genre');
        return view('/books.show',["books"=> $books]);

    }

    public function create() {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')
                ->with('error', 'Only administrators can add new books.');
        }
        
        $genre = Genre::all();
        return view('books.create' , ['genre' => $genre]);
    }

    public function store(Request $request) {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')
                ->with('error', 'Only administrators can add new books.');
        }

        $validated = $request->validate([
            'title' => 'required|max:100',
            'author' => ['required', 'max:100', 'regex:/^[A-Za-z\s]+$/'],
            'about' => 'required',
            'number_of_books' => 'required|integer|min:1|max:20', 
            'genre_id' => 'required|exists:genres,id',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/books', $filename);
            $validated['cover_image'] = $filename;
        }

        // Add the current user's ID to the validated data
        $validated['user_id'] = auth()->id();
        
        Books::create($validated);
    return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    public function destroy(Books $books) {
        $books->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

    public function favorites()
    {
        $favorites = auth()->user()->favorites()->with(['genre'])->latest()->paginate(12);
        return view('user.favorite-books', compact('favorites'));
    }

    public function addToFavorites(Books $book)
    {
        auth()->user()->favorites()->attach($book);
        return back()->with('success', 'Book added to favorites successfully.');
    }

    public function removeFromFavorites(Books $book)
    {
        auth()->user()->favorites()->detach($book);
        return back()->with('success', 'Book removed from favorites successfully.');
    }
}
