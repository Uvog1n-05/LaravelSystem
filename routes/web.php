    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\BooksController;
    use App\Http\Controllers\AuthController;

    Route::get('/', function () {
        return view('welcome');
    });
    Route::middleware('guest')->controller(AuthController::class)->group(function () {

    Route::get('/register','showRegister')->name('show.register');
    Route::get('/login','showLogin')->name('show.login');
    Route::post('/register','register')->name('register');
    Route::post('/login','login')->name('login');
    
 });

    Route::post('/logout',[AuthController::class,'logout'])->name('logout');

    Route::middleware('auth')->controller(BooksController::class)->group(function () {     
    Route::get('/books', 'index')->name ('books.index');
    Route::get('/books/create', 'create')->name ('books.create');
    Route::get('/books/{books}', 'show')->name ('books.show');
    Route::post('/books',  'store')->name ('books.store');
    Route::delete('/books/{books}', 'destroy')->name ('books.destroy');
    });

