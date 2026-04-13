<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        // Priorizamos la URL del .env, con fallback a localhost
        $this->apiUrl = rtrim(env('API_URL', 'http://127.0.0.1:8000/api'), '/');
    }

    public function index()
    {
        $token = session('api_token');
        $error = null;

        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesión expirada.');
        }

        try {
            $response = Http::withToken($token)->acceptJson()
                ->get("{$this->apiUrl}/dashboard");

            if ($response->successful()) {
                $dashboard = $response->json();
            } else {
                $dashboard = $this->getDefaultData();
                $error = 'El servidor de datos respondió con error: ' . ($response->json()['message'] ?? 'Acceso denegado');
            }
        } catch (\Exception $e) {
            $dashboard = $this->getDefaultData();
            $error = 'No hay conexión con el servidor central de Solare.';
        }

        return view('paginas/Dashboard', compact('dashboard', 'error'));
    }

    /**
     * Datos por defecto para evitar que la vista explote si la API falla
     */
    private function getDefaultData()
    {
        return [
            'ventas_mes' => ['cantidad' => 0, 'total' => '$0.00'],
            'piezas_stock' => 0,
            'valor_inventario' => '$0.00',
            'pedidos_activos' => 0,
            'pedidos_recientes' => [],
            'mas_vendidos' => [],
            'stock_critico' => [],
            'ajustes_manuales_24h' => 0
        ];
    }
}
