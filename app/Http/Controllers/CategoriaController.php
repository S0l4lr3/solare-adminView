<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoriaController extends Controller
{
    protected $apiUrl = '127.0.0.1:8000/api';

    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');

        // Petición a la API del backend en Railway para categorías
        $response = Http::get("{$this->apiUrl}/categorias", [
            'busqueda' => $busqueda
        ]);

        $categorias = $response->successful() ? $response->json() : [];

        return view('categorias/categorias', compact('categorias', 'busqueda'));
    }

    public function create()
    {
        return view('categorias/categorias-crear');
    }

    // Guardar nueva categoría en el Backend de Railway
    public function store(Request $request)
    {
        $token = session('api_token');
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $response = Http::withToken($token)->post("{$this->apiUrl}/categorias", [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        if ($response->successful()) {
            return redirect()->route('categorias.index')->with('success', 'Categoría guardada exitosamente.');
        }

        // Si hay error, intentamos leer el mensaje que enviamos desde el backend mejorado
        $errorMsg = $response->json('error') ?? $response->json('errores.nombre.0') ?? 'Error desconocido en el servidor.';
        
        return back()->with('error', 'Error al guardar: ' . $errorMsg);
    }

    public function edit($id)
    {
        $response = Http::get("{$this->apiUrl}/categorias/{$id}");
        $categoria = $response->successful() ? $response->json() : null;

        return view('categorias/categorias-editar', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $response = Http::put("{$this->apiUrl}/categorias/{$id}", [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        if ($response->successful()) {
            return redirect()->route('categorias.index')->with('success', 'Categoría actualizada.');
        }

        return back()->with('error', 'Error al actualizar la categoría.');
    }

    public function destroy($id)
    {
        Http::delete("{$this->apiUrl}/categorias/{$id}");
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada.');
    }
}