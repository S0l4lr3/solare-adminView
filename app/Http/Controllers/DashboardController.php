<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://127.0.0.1:8000/api');
    }
    public function index()
    {
        $token = session('api_token');
        $error = null;

        // Volviendo a la ruta oficial protegida
        $response = Http::withToken($token)->acceptJson()
            ->get("{$this->apiUrl}/dashboard");

        if ($response->successful()) {
            $dashboardData = $response->json();
        } else {
            $dashboardData = [];
            $error = 'Error al conectar con el servidor de estadísticas: ' . ($response->json()['message'] ?? 'Error desconocido');
        }

        // Sincronización de llaves para Solare Muebles
        $dashboard = array_merge([
            'ventas_mes' => ['cantidad' => 0, 'total' => '$0'],
            'piezas_stock' => 0,
            'pedidos_activos' => 0,
            'pedidos_recientes' => [],
            'mas_vendidos' => [],
            'ajustes_manuales_24h' => 0,
            'stock_critico' => [],
            'valor_inventario' => '$0',
        ], $dashboardData);

        return view('paginas/Dashboard', compact('dashboard', 'error'));
    }

}