<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books; 
use App\Models\Genre;
class BooksController extends Controller
{
    public function index(Request $request) {
        $query = \App\Models\Books::with('genre');
        
        if ($request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('author', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('genre', function($q) use ($searchTerm) {
                      $q->where('genre_name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }
        
        $books = $query->orderBy('created_at', 'desc')->paginate(12);
        $books->appends(['search' => $request->search]);
        
        return view('books.index', ['books' => $books]);
    }

    public function show(Books $books) {

         $books ->load('genre');
        return view('/books.show',["books"=> $books]);

    }

    public function create() {
    
        $genre = Genre::all();

    return view('books.create' , ['genre' => $genre]);
    }

    public function store(Request $request) {

     $validated = $request->validate([
         'title' => 'required|max:100',
        'author' => ['required', 'max:100', 'regex:/^[A-Za-z\s]+$/'],
         'about' => 'required',
        'number_of_books' => 'required|integer|min:1|max:20', 
        'genre_id' => 'required|exists:genres,id',
]);
        Books::create($validated);
        return redirect()->route('books.index') ->with('success', 'Book created successfully.');
    }

    public function destroy(Books $books) {

   
        $books->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    

    }
}
