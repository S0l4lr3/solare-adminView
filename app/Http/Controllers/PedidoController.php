<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PedidoController extends Controller
{
    protected $apiUrl = 'https://solare-backend-production.up.railway.app/api';

    /**
     * Muestra el historial de pedidos consumiendo el Nodo Backend.
     */
    public function index(Request $request)
    {
        $token = session('api_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesión expirada.');
        }

        // Petición al Backend solicitando pedidos de la mueblería
        $response = Http::withToken($token)->get("{$this->apiUrl}/pedidos");

        $pedidos = $response->successful() ? $response->json() : [];

        // Cambiamos la vista de estática a dinámica para Ventas/Pedidos
        return view('paginas.Ventas', compact('pedidos'));
    }

    /**
     * Actualizar estado del pedido (ej: Entregado, Cancelado)
     */
    public function update(Request $request, $id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->put("{$this->apiUrl}/pedidos/{$id}", [
            'estado' => $request->estado
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Pedido actualizado correctamente.');
        }

        return back()->with('error', 'No se pudo actualizar el pedido.');
    }
}