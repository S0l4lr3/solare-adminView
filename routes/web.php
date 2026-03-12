<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InventarioController;


// login y ruta principal
Route::get('/', [AuthController::class, 'Formulario'])->name('login');
Route::get('/login', [AuthController::class, 'Formulario']);
Route::post('/login', [AuthController::class, 'Login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');
// registro de administrador
Route::get('/register', [AuthController::class, 'Registro'])->name('register');
// -----------------------------------------------------------------------------

Route::get('/dashboard', function () {return view('paginas.Dashboard');})->name('dashboard');

Route::get('/Inventario', [InventarioController::class, 'index'])->name('inventario');

Route::get('/Ventas', function () {return view('paginas.Ventas');});
