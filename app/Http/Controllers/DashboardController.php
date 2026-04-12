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

        // Asegúrate de que apunte a /Dashboard, que es la que tiene la lógica de estadísticas
        $response = Http::withToken($token)->acceptJson()
            ->get("{$this->apiUrl}/Dashboard");

        $dashboardData = $response->successful() ? $response->json() : [];

        // Ahora sí, si la API responde correctamente, estas llaves se sobreescribirán
        $dashboard = array_merge([
            'ventas_mes' => ['cantidad' => 0, 'total' => '$0'],
            'piezas_stock' => 0,
            'pedidos_activos' => 0,
            'pedidos_recientes' => [],
            'mas_vendidos' => [],
        ], $dashboardData);

        return view('paginas/Dashboard', compact('dashboard'));
    }

}