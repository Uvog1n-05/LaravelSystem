    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\BooksController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\BookBorrowingController;
    use App\Http\Controllers\ProfileController;

    Route::get('/', function () {
        return view('welcome');
    });

    // Home route that redirects to appropriate dashboard
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::middleware('guest')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/register','showRegister')->name('show.register');
            Route::get('/login','showLogin')->name('show.login');
            Route::post('/register','register')->name('register');
            Route::post('/login','login')->name('login');
        });

        // Password Reset Routes
        Route::get('forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('password.request');
        Route::post('forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('password.email');
        Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
            ->name('password.reset');
        Route::post('reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
            ->name('password.update');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Authenticated User Routes
    Route::middleware('auth')->group(function () {
        // User Dashboard
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('user.dashboard');

        // User Profile Routes
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
            Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
            Route::get('/favorite-books', [BooksController::class, 'favorites'])->name('favorite');
        });

        // Book Borrowing Routes
        Route::prefix('borrowing')->name('books.')->group(function () {
            Route::get('/history', [BookBorrowingController::class, 'history'])->name('history');
            Route::post('/{book}/borrow', [BookBorrowingController::class, 'borrow'])->name('borrow');
            Route::put('/{book}/return', [BookBorrowingController::class, 'returnBook'])->name('return');
            Route::put('/{book}/cancel-return', [BookBorrowingController::class, 'cancelReturn'])->name('cancel-return');
            Route::post('/{book}/extend', [BookBorrowingController::class, 'extend'])->name('extend');
        });

        // Books Routes
        Route::prefix('books')->group(function () {
            Route::get('/', [BooksController::class, 'index'])->name('books.index');
            Route::get('/create', [BooksController::class, 'create'])->name('books.create');
            Route::post('/', [BooksController::class, 'store'])->name('books.store');
            Route::get('/{books}', [BooksController::class, 'show'])->name('books.show');
            Route::delete('/{books}', [BooksController::class, 'destroy'])->name('books.destroy');
            Route::post('/{book}/favorite', [BooksController::class, 'addToFavorites'])->name('user.favorite.add');
            Route::delete('/{book}/favorite', [BooksController::class, 'removeFromFavorites'])->name('user.favorite.remove');
        });
    });

    // Borrow Request Routes
    Route::middleware('auth')->prefix('borrow-requests')->name('borrow-requests.')->group(function () {
        Route::post('/{book}', [App\Http\Controllers\BorrowRequestsController::class, 'store'])->name('store');
        Route::get('/my-requests', [App\Http\Controllers\BorrowRequestsController::class, 'userRequests'])->name('user');
    });

    // Admin Routes
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
        Route::patch('/users/{user}/role', [App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('admin.users.update-role');
        
        // Admin Borrow Request Routes
        Route::get('/borrow-requests', [App\Http\Controllers\BorrowRequestsController::class, 'index'])->name('admin.borrow-requests');
        Route::patch('/borrow-requests/{request}/{action}', [App\Http\Controllers\BorrowRequestsController::class, 'process'])
            ->name('admin.borrow-requests.process')
            ->where('action', 'approve|decline');
        // Admin approve return (verify a user's return request)
        Route::patch('/borrowings/{borrowing}/approve-return', [App\Http\Controllers\BookBorrowingController::class, 'approveReturn'])
            ->name('admin.borrowings.approve-return');
    Route::get('/books', [App\Http\Controllers\AdminController::class, 'books'])->name('admin.books');
    Route::get('/genres', [App\Http\Controllers\AdminController::class, 'genres'])->name('admin.genres');
    Route::post('/genres', [App\Http\Controllers\AdminController::class, 'store'])->name('admin.genres.store');
    Route::delete('/genres/{genre}', [App\Http\Controllers\AdminController::class, 'destroy'])->name('admin.genres.destroy');
    Route::patch('/genres/{genre}', [App\Http\Controllers\AdminController::class, 'update'])->name('admin.genres.update');
    Route::get('/borrowings', [App\Http\Controllers\BookBorrowingController::class, 'allBorrowings'])->name('admin.borrowings');
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

    Route::get('/user/favorite-books', [BooksController::class, 'favorites'])->name('user.favorite');
    Route::post('/books/{book}/favorite', [BooksController::class, 'addToFavorites'])->name('user.favorite.add');
    Route::delete('/books/{book}/favorite', [BooksController::class, 'removeFromFavorites'])->name('user.favorite.remove');

    });

