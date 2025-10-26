    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\BooksController;
    use App\Http\Controllers\AuthController;

    Route::get('/', function () {
        return view('welcome');
    });

    // Home route that redirects to appropriate dashboard
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::middleware('guest')->controller(AuthController::class)->group(function () {

    Route::get('/register','showRegister')->name('show.register');
    Route::get('/login','showLogin')->name('show.login');
    Route::post('/register','register')->name('register');
    Route::post('/login','login')->name('login');
    
 });

    Route::post('/logout',[AuthController::class,'logout'])->name('logout');

    // Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::patch('/users/{user}/role', [App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('admin.users.update-role');
    Route::get('/books', [App\Http\Controllers\AdminController::class, 'books'])->name('admin.books');
    Route::get('/genres', [App\Http\Controllers\AdminController::class, 'genres'])->name('admin.genres');
    Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');
});

Route::middleware('auth')->controller(BooksController::class)->group(function () {     
    Route::get('/books', 'index')->name ('books.index');
    Route::get('/books/create', 'create')->name ('books.create');
    Route::get('/books/{books}', 'show')->name ('books.show');
    Route::post('/books',  'store')->name ('books.store');
    Route::delete('/books/{books}', 'destroy')->name ('books.destroy');


    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    // User Profile Routes
    Route::get('/user/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('user.profile');
    Route::put('/user/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('user.profile.update');
    Route::put('/user/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('user.profile.password');

    // Book Borrowing Routes
    Route::middleware('auth')->group(function () {
        Route::post('/books/{book}/borrow', [App\Http\Controllers\BookBorrowingController::class, 'borrow'])->name('books.borrow');
        Route::put('/books/{book}/return', [App\Http\Controllers\BookBorrowingController::class, 'return'])->name('books.return');
        Route::post('/books/{book}/extend', [App\Http\Controllers\BookBorrowingController::class, 'extend'])->name('books.extend');
        Route::get('/books/history', [App\Http\Controllers\BookBorrowingController::class, 'history'])->name('books.history');
    });

    Route::get('/user/favorite-books', [BooksController::class, 'favorites'])->name('user.favorite');
    Route::post('/books/{book}/favorite', [BooksController::class, 'addToFavorites'])->name('user.favorite.add');
    Route::delete('/books/{book}/favorite', [BooksController::class, 'removeFromFavorites'])->name('user.favorite.remove');

    });

