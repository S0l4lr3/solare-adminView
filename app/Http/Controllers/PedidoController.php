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

    public function index()
    {
        $token = session('api_token');
        $response = Http::withToken($token)->acceptJson()->get("{$this->apiUrl}/pedidos");
        $pedidos = $response->successful() ? $response->json() : [];

        return view('pedidos/pedidos', compact('pedidos'));
    }

    public function actualizarEstadoEnvio(Request $request, $id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->acceptJson()->put(
            "{$this->apiUrl}/pedidos/{$id}",
            ['estado_envio' => $request->estado_envio]
        );

        if ($response->successful()) {
            return back()->with('success', 'Estado de envío actualizado.');
        }

        return back()->with('error', 'Error al actualizar: ' . $response->body());
    }
}