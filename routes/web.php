<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\ReservasController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (login, registration, logout)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Dashboard route, accessible only if the user is authenticated
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Route to manage logout using a separate controller
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Dashboard page view route (if needed)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Routes for user management
Route::middleware(['auth'])->group(function () {
    // Route to display the list of users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

});

// Routes for user registration
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit'); // Formulario de edición
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update'); // Actualización de usuario
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy'); // Eliminación
    Route::get('/users/{id}/change-password', [UserController::class, 'showChangePasswordForm'])->name('users.change_password');
    Route::post('/users/{id}/change-password', [UserController::class, 'changePassword'])->name('users.update_password');
});

Route::get('/canchas', [CanchaController::class, 'index'])->name('canchas.index');

Route::get('/reservar', [ReservasController::class, 'mostrarDetalle'])->name('reservar');

// Rutas para agregar en routes/web.php

// Listado de canchas
Route::get('/canchas', [App\Http\Controllers\CanchaController::class, 'index'])->name('canchas.index');

// Mostrar detalles de una cancha
Route::get('/cancha/{id}', [App\Http\Controllers\CanchaController::class, 'show'])->name('canchas.show');

// Formulario de reserva
Route::get('/reservar', [App\Http\Controllers\CanchaController::class, 'reservar'])->name('reservar');

// Procesar la reserva
Route::post('/reservar', [App\Http\Controllers\CanchaController::class, 'procesarReserva'])->name('reservar.procesar');
