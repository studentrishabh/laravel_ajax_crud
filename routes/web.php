
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
// use app/Http/Middleware/Authenticate.php;

Route::get('/', function () {
    return view('welcome');
});
 // Importing custom middleware if needed

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout Route


// Protecting routes with the 'auth' middleware
Route::middleware([Authenticate::class])->group(function () {
    // Dashboard Route (Protected)
 Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('users.index');

Route::get('/', [UserController::class, 'index']);
Route::get('/users', [UserController::class, 'getUsers']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'edit']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});
