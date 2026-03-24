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

        return view('/productos/Productos-editar', compact('producto', 'categorias'));
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

    // Mostrar vista de gestión de imágenes
    public function imagenes($id)
    {
        $producto = Producto::with('imagenes')->findOrFail($id);
        return view('/productos/Productos-imagenes', compact('producto'));
    }

    // Subir nuevas imágenes
    public function storeImagenes(Request $request, $id)
    {
        $request->validate([
            'imagenes' => 'required|array',
            'imagenes.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $producto = Producto::findOrFail($id);
        $orden = $producto->imagenes()->max('orden') + 1;
        $tienePrincipal = $producto->imagenes()->where('es_principal', 1)->exists();

        foreach ($request->file('imagenes') as $file) {
            $path = $file->store('productos', 'public');

            ImagenProducto::create([
                'producto_id' => $producto->id,
                'url' => $path,
                'es_principal' => !$tienePrincipal ? 1 : 0, // la primera sube como principal si no hay ninguna
                'orden' => $orden++,
            ]);

            $tienePrincipal = true;
        }

        return redirect()->route('productos.imagenes', $id)
            ->with('success', 'Imágenes agregadas correctamente.');
    }

    // Eliminar una imagen
    public function destroyImagen($imagenId)
    {
        $imagen = ImagenProducto::findOrFail($imagenId);
        $productoId = $imagen->producto_id;

        \Storage::disk('public')->delete($imagen->url);
        $imagen->delete();

        return redirect()->route('productos.imagenes', $productoId)
            ->with('success', 'Imagen eliminada.');
    }

    // Marcar como imagen principal
    public function setPrincipal($imagenId)
    {
        $imagen = ImagenProducto::findOrFail($imagenId);
        $productoId = $imagen->producto_id;

        ImagenProducto::where('producto_id', $productoId)->update(['es_principal' => 0]);
        $imagen->update(['es_principal' => 1]);

        return redirect()->route('productos.imagenes', $productoId)
            ->with('success', 'Imagen principal actualizada.');
    }
}