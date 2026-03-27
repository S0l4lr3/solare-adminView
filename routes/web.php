<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PedidoController;


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
Route::get('productos/{id}/imagenes', [ProductoController::class, 'gestionarImagenes'])->name('productos.imagenes');
Route::post('productos/{id}/imagenes', [ProductoController::class, 'subirImagenes'])->name('imagenes.store');
Route::delete('imagenes/{id}', [ProductoController::class, 'eliminarImagen'])->name('imagenes.destroy');
Route::patch('imagenes/{id}/principal', [ProductoController::class, 'marcarPrincipal'])->name('imagenes.principal');
Route::patch('productos/{id}/toggle-estatus', [ProductoController::class, 'toggleEstatus'])->name('productos.toggleEstatus');

// CRUD de Categorías conectado a API Railway (de la rama alex)
Route::resource('categorias', CategoriaController::class);

// CRUD de Usuarios conectado a API Railway (de la rama alex)
Route::resource('usuarios', UsuarioController::class);
Route::patch('usuarios/{id}/toggle-estatus', [UsuarioController::class, 'toggleEstatus'])->name('usuarios.toggleEstatus');
Route::get('usuarios/{id}/edit', [UsuarioController::class, 'show'])->name('usuarios.edits');
Route::patch('usuarios/{id}', [UsuarioController::class, 'patch'])->name('usuarios.patch');

// Sección de Inventario conectado a la API
Route::get('/Inventario', [InventarioController::class, 'index'])->name('inventario');
Route::put('/Inventario/{id}', [InventarioController::class, 'updateStock'])->name('inventario.update');

// Sección de Ventas/Pedidos conectado a la API
Route::get('/Ventas', [PedidoController::class, 'index'])->name('pedidos.index');
Route::put('/Ventas/{id}', [PedidoController::class, 'update'])->name('pedidos.update');

//obtener roles
Route::get('/roles', [UsuarioController::class, 'getRoles'])->name('usuarios.getRoles');
