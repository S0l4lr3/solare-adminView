<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoriaController extends Controller
{
    protected $apiUrl = 'https://solare-backend-production.up.railway.app/api';

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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $response = Http::post("{$this->apiUrl}/categorias", [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        if ($response->successful()) {
            return redirect()->route('categorias.index')->with('success', 'Categoría guardada en Railway.');
        }

        return back()->with('error', 'Error al guardar la categoría: ' . $response->body());
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