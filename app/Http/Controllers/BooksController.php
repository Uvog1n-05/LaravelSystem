<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books; 
use App\Models\Genre;
class BooksController extends Controller
{
    public function index(Request $request) {
        if ($request->search) {
            $searchTerm = $request->search;
            $books = Books::with('genre')
                ->where(function($q) use ($searchTerm) {
                    $q->where('title', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('author', 'LIKE', "%{$searchTerm}%")
                      ->orWhereHas('genre', function($q) use ($searchTerm) {
                          $q->where('genre_name', 'LIKE', "%{$searchTerm}%");
                      });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(12);
            
            $books->appends(['search' => $request->search]);
            return view('books.index', [
                'books' => $books,
                'featuredBooks' => collect(), // Empty for search results
                'genres' => Genre::withCount('books')->get()
            ]);
        }

        // Get featured books (newest 8 books for carousel, showing 4 at a time)
        $featuredBooks = Books::with('genre')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Get all genres with their books
        $genres = Genre::with(['books' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();

        return view('books.index', [
            'books' => Books::with('genre')->orderBy('created_at', 'desc')->paginate(12),
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
