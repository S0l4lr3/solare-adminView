<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InventarioController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://127.0.0.1:8000/api');
    }

    /**
     * Muestra el inventario consumiendo el Nodo Backend de Solare.
     */
    public function index()
    {
        // 1. Recuperamos el pasaporte de red de la sesión
        $token = session('api_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesión de red expirada.');
        }

        // 2. Petición HTTP al Backend (Nodo Central) solicitando el stock
        // Actualizada a la URL pública embebida
        $response = Http::withToken($token)->get("{$this->apiUrl}/inventario");

        if ($response->successful()) {
            $stock = $response->json()['data'] ?? [];
            return view('paginas.Inventario', compact('stock'));
        }

        // Si el nodo de inventario no responde o rechaza el token
        return view('paginas.Inventario')->with('error', 'No se pudo sincronizar con el nodo de inventario central.');
    }

    /**
     * Actualización rápida de stock (si el backend lo permite)
     */
    public function updateStock(Request $request, $id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->put("{$this->apiUrl}/inventario/{$id}", [
            'cantidad' => $request->cantidad
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Inventario actualizado.');
        }

        return back()->with('error', 'Error al actualizar stock.');
    }
}