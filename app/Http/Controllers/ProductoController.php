<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ImagenProducto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Listar productos + cargar categorías para el formulario
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');

        $productos = Producto::with(['categoria', 'imagenPrincipal'])
            ->when($busqueda, function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where('nombre', 'LIKE', "%{$busqueda}%")
                        ->orWhere('sku_base', 'LIKE', "%{$busqueda}%")
                        ->orWhereHas('categoria', function ($q2) use ($busqueda) {
                            $q2->where('nombre', 'LIKE', "%{$busqueda}%");
                        });

                    // Solo busca por ID si lo que escribieron es un número
                    if (is_numeric($busqueda)) {
                        $q->orWhere('id', $busqueda);
                    }
                });
            })
            ->get();

        $categorias = Categoria::all();

        return view('/productos/Productos', compact('productos', 'categorias', 'busqueda'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('/productos/Productos-crear', compact('categorias'));
    }

    // Guardar nuevo producto
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio_base' => 'required|numeric',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'sku_base' => 'nullable|string|max:100',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_base' => $request->precio_base,
            'sku_base' => $request->sku_base,
            'categoria_id' => $request->categoria_id,
            'activo' => 1,
        ]);

        // Si subió imagen, guardarla
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos', 'public');

            ImagenProducto::create([
                'producto_id' => $producto->id,
                'url' => $path,
                'es_principal' => 1,
                'orden' => 1,
            ]);
        }

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit($id)
    {
        $producto = Producto::with('imagenPrincipal')->findOrFail($id);
        $categorias = Categoria::all();

        return view('/productos/paginas/Productos-editar', compact('producto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio_base' => 'required|numeric',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'sku_base' => 'nullable|string|max:100',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_base' => $request->precio_base,
            'sku_base' => $request->sku_base,
            'categoria_id' => $request->categoria_id,
        ]);

        if ($request->hasFile('imagen')) {
            // Elimina imagen anterior si existe
            $producto->imagenPrincipal()?->delete();

            $path = $request->file('imagen')->store('productos', 'public');

            ImagenProducto::create([
                'producto_id' => $producto->id,
                'url' => $path,
                'es_principal' => 1,
                'orden' => 1,
            ]);
        }

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    // Eliminar producto y sus imágenes
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->imagenes()->delete();
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado.');
    }

    // Activar / Desactivar producto
    public function toggleEstatus($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->activo = $producto->activo == 1 ? 0 : 1;
        $producto->save();

        return redirect()->route('productos.index')
            ->with('success', 'Estatus actualizado.');
    }
}