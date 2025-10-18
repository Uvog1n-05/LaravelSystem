<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/register',[AuthController::class,'showRegister'])->name('show.register');
Route::get('/login',[AuthController::class,'showLogin'])->name('show.login');
Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');




Route::get('/books', [BooksController::class, 'index'])->name ('books.index');
Route::get('/books/create', [BooksController::class, 'create'])->name ('books.create');
Route::get('/books/{books}', [BooksController::class, 'show'])->name ('books.show');
Route::post('/books', [BooksController::class, 'store'])->name ('books.store');
Route::delete('/books/{books}', [BooksController::class, 'destroy'])->name ('books.destroy');
