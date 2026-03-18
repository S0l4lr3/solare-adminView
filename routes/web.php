<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuarioController;


Route::get('/Ventas', function () {
    return view('/paginas/Ventas');
});



route::view('/', '/paginas/Dashboard');
route::view('/Dashboard', '/paginas/Dashboard');
route::view('/productos', '/paginas/Productos');
route::view('/categorias', '/paginas/Categorias');
route::view('/Pedidos', '/paginas/Pedidos');



/*
|--------------------------------------------------------------------------
| Rutas para los productos
|--------------------------------------------------------------------------
*/

Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
Route::patch('/productos/{id}/toggle-estatus', [ProductoController::class, 'toggleEstatus'])->name('productos.toggleEstatus');
Route::get('/productos/crear', [ProductoController::class, 'create'])->name('productos.create');
Route::get('/productos/{id}/editar', [ProductoController::class, 'edit'])->name('productos.edit');
Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');


/*
|--------------------------------------------------------------------------
| Rutas para las categorias
|--------------------------------------------------------------------------
*/

Route::get('/categorias',                         [CategoriaController::class, 'index'])->name('categorias.index');
Route::get('/categorias/crear',                   [CategoriaController::class, 'create'])->name('categorias.create');
Route::post('/categorias',                        [CategoriaController::class, 'store'])->name('categorias.store');
Route::get('/categorias/{id}/editar',             [CategoriaController::class, 'edit'])->name('categorias.edit');
Route::put('/categorias/{id}',                    [CategoriaController::class, 'update'])->name('categorias.update');
Route::delete('/categorias/{id}',                 [CategoriaController::class, 'destroy'])->name('categorias.destroy');


/*
|--------------------------------------------------------------------------
| Rutas para los empleados
|--------------------------------------------------------------------------
*/

Route::get('/usuarios',              [UsuarioController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/crear',        [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios',             [UsuarioController::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/{id}/editar',  [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{id}',         [UsuarioController::class, 'update'])->name('usuarios.update');
Route::delete('/usuarios/{id}',      [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

