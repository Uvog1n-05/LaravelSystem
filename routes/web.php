<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;


Route::get('/', function () {
    return view('welcome');
});





Route::get('/books', [BooksController::class, 'index'])->name ('books.index');
Route::get('/books/create', [BooksController::class, 'create'])->name ('books.create');
Route::get('/books/{books}', [BooksController::class, 'show'])->name ('books.show');
Route::post('/books', [BooksController::class, 'store'])->name ('books.store');
Route::delete('/books/{books}', [BooksController::class, 'destroy'])->name ('books.destroy');
