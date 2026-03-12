<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InventarioController extends Controller
{
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
        // Usamos la URL del servidor local de desarrollo
        $response = Http::withToken($token)->get('http://127.0.0.1:8000/api/inventario');

        if ($response->successful()) {
            $stock = $response->json();
            return view('paginas.Inventario', compact('stock'));
        }

        // Si el nodo de inventario no responde o rechaza el token
        return view('paginas.Inventario')->with('error', 'No se pudo sincronizar con el nodo de inventario central.');
    }
}
