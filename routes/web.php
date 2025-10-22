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

    Route::get('/user/user-profile', function () {
        return view('user.user-profile');
    })->name('user.profile');

    Route::get('/user/favorite-books', function () {
        return view('user.favorite-books');
    })->name('user.favorite');   

    });

