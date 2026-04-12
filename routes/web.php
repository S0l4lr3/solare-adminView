<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\materialesController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\DashboardController;

// login y ruta principal
Route::get('/', [AuthController::class, 'Formulario'])->name('login');
Route::get('/login', [AuthController::class, 'Formulario']);
Route::post('/login', [AuthController::class, 'Login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');
// registro de administrador
Route::get('/register', [AuthController::class, 'Registro'])->name('register');
// -----------------------------------------------------------------------------

Route::get('/dashboard', function () {
    return view('paginas.Dashboard');
})->name('dashboard');

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
Route::get('/clientes', [UsuarioController::class, 'indexc'])->name('clientes.index');
Route::patch('usuarios/{id}/toggle-estatus', [UsuarioController::class, 'toggleEstatus'])->name('usuarios.toggleEstatus');
Route::get('usuarios/{id}/edit', [UsuarioController::class, 'show'])->name('usuarios.edits');
Route::patch('usuarios/{id}', [UsuarioController::class, 'patch'])->name('usuarios.patch');

// CRUD de Roles
Route::resource('roles', RolController::class);

// Sección de Inventario conectado a la API
Route::get('/Inventario', [InventarioController::class, 'index'])->name('inventario');
Route::get('/inventario', [InventarioController::class, 'index']); 
Route::get('/inventario/exportar/{formato}', [InventarioController::class, 'exportar'])->name('inventario.exportar');
Route::put('/Inventario/{id}', [InventarioController::class, 'updateStock'])->name('inventario.update');

// Sección de Ventas/Pedidos conectado a la API
// Route::get('/Ventas', [PedidoController::class, 'index'])->name('pedidos.index');
// Route::put('/Ventas/{id}', [PedidoController::class, 'update'])->name('pedidos.update');

// CRUD de Materiales
Route::resource('materiales', materialesController::class);
Route::get('materiales/{id}/edit', [materialesController::class, 'show'])->name('materiales.edits');
Route::put('materiales/{id}', [materialesController::class, 'update'])->name('materiales.updates');
Route::delete('materiales/{id}', [materialesController::class, 'destroy'])->name('materiales.destroys');

//pedidos
Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
Route::put('/pedidos/{id}/estado-envio', [PedidoController::class, 'actualizarEstadoEnvio'])->name('pedidos.estadoEnvio');

//dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');