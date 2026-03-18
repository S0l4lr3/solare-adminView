<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;


// login y ruta principal
Route::get('/', [AuthController::class, 'Formulario'])->name('login');
Route::get('/login', [AuthController::class, 'Formulario']);
Route::post('/login', [AuthController::class, 'Login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');
// registro de administrador
Route::get('/register', [AuthController::class, 'Registro'])->name('register');
// -----------------------------------------------------------------------------

Route::get('/dashboard', function () {return view('paginas.Dashboard');})->name('dashboard');

// CRUD de Productos conectado a API Railway (de la rama alex)
Route::resource('productos', ProductoController::class);

// CRUD de Categorías conectado a API Railway (de la rama alex)
Route::resource('categorias', CategoriaController::class);

Route::get('/Inventario', [InventarioController::class, 'index'])->name('inventario');

Route::get('/Ventas', function () {return view('paginas.Ventas');});
