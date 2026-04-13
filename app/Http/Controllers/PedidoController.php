<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PedidoController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://127.0.0.1:8000/api');
    }

    public function index(Request $request)
    {
        $token = session('api_token');
        
        // Parámetros a enviar a la API
        $params = [
            'search' => $request->search,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado_envio' => $request->estado_envio,
            'estado_pago' => $request->estado_pago,
        ];

        $response = Http::withToken($token)->acceptJson()->get("{$this->apiUrl}/pedidos", $params);
        $pedidos = $response->successful() ? $response->json() : [];

        return view('pedidos/pedidos', compact('pedidos'));
    }

    public function show($id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->acceptJson()->get("{$this->apiUrl}/pedidos/{$id}");
        $pedido = $response->successful() ? $response->json() : null;

        if (!$pedido) {
            return redirect()->route('pedidos.index')->with('error', 'Pedido no encontrado.');
        }

        return view('pedidos/pedido-detalle', compact('pedido'));
    }

    public function update(Request $request, $id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->acceptJson()->put(
            "{$this->apiUrl}/pedidos/{$id}",
            $request->only(['estado_envio', 'estado_pago', 'notas'])
        );

        if ($response->successful()) {
            return back()->with('success', 'Pedido actualizado correctamente.');
        }

        return back()->with('error', 'Error al actualizar: ' . $response->body());
    }

    public function actualizarEstadoEnvio(Request $request, $id)
    {
        // Redirigimos al método update genérico
        return $this->update($request, $id);
    }
}