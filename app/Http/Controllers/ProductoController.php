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

        // Petición a la API del backend en Railway
        $response = Http::get("{$this->apiUrl}/productos", [
            'busqueda' => $busqueda
        ]);

        $productos = $response->successful() ? $response->json() : [];

        // También traemos categorías de la API
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

    // Guardar nuevo producto en el Backend de Railway
    public function store(Request $request)
    {
        $data = $request->all();

        // Enviamos los datos a la API
        $response = Http::post("{$this->apiUrl}/productos", $data);

        if ($response->successful()) {
            return redirect()->route('productos.index')->with('success', 'Producto guardado exitosamente en Railway.');
        }

        return back()->with('error', 'Error al guardar en el servidor: ' . $response->body());
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

    public function destroy($id)
    {
        Http::delete("{$this->apiUrl}/productos/{$id}");
        return redirect()->route('productos.index');
    }
}