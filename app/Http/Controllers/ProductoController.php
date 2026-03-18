<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductoController extends Controller
{
    protected $apiUrl = 'https://solare-backend-production.up.railway.app/api';

    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');

        // Petición a la API del backend
        $response = Http::get("{$this->apiUrl}/productos", [
            'search' => $busqueda
        ]);

        $productos = $response->successful() ? $response->json() : [];

        // Traer categorías de la API
        $categoriasResponse = Http::get("{$this->apiUrl}/categorias");
        $categorias = $categoriasResponse->successful() ? $categoriasResponse->json() : [];

        return view('/productos/Productos', compact('productos', 'categorias', 'busqueda'));
    }

    public function create()
    {
        $categoriasResponse = Http::get("{$this->apiUrl}/categorias");
        $categorias = $categoriasResponse->successful() ? $categoriasResponse->json() : [];
        return view('/productos/Productos-crear', compact('categorias'));
    }

    public function store(Request $request)
    {
        $response = Http::post("{$this->apiUrl}/productos", $request->all());

        if ($response->successful()) {
            return redirect()->route('productos.index')->with('success', 'Producto guardado.');
        }

        return back()->with('error', 'Error al guardar en el backend.');
    }

    public function edit($id)
    {
        $response = Http::get("{$this->apiUrl}/productos/{$id}");
        $producto = $response->successful() ? $response->json() : null;

        $categoriasResponse = Http::get("{$this->apiUrl}/categorias");
        $categorias = $categoriasResponse->successful() ? $categoriasResponse->json() : [];

        return view('/productos/Productos-editar', compact('producto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $response = Http::put("{$this->apiUrl}/productos/{$id}", $request->all());

        if ($response->successful()) {
            return redirect()->route('productos.index')->with('success', 'Producto actualizado.');
        }

        return back()->with('error', 'Error al actualizar.');
    }

    // LÍNEAS 81-86: MÉTODO PARA CAMBIAR EL ESTATUS (IMPORTANTE)
    public function toggleEstatus($id)
    {
        $response = Http::patch("{$this->apiUrl}/productos/{$id}/toggle-estatus");

        if ($response->successful()) {
            return back()->with('success', 'Estado del producto actualizado correctamente.');
        }

        return back()->with('error', 'No se pudo cambiar el estado del producto.');
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->apiUrl}/productos/{$id}");
        return redirect()->route('productos.index');
    }
}