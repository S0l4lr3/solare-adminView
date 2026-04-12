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
    protected $apiUrl = 'http://127.0.0.1:8000/api';

    /**
     * Muestra el inventario consumiendo el Nodo Backend de Solare.
     */
    public function index(Request $request)
    {
        $token = session('api_token');
        $stock = [];
        $error = null;

        $params = [
            'search' => $request->get('search'),
            'categoria_id' => $request->get('categoria_id'),
            'material_id' => $request->get('material_id'),
            'sort' => $request->get('sort'),
            'order' => $request->get('order'),
            'stock_bajo' => $request->has('stock_bajo') ? 1 : null
        ];

        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesión de red expirada.');
        }

        try {
            $response = Http::withToken($token)->timeout(10)->get("{$this->apiUrl}/inventario", array_filter($params));

            if ($response->successful()) {
                $stock = $response->json()['data'] ?? [];
            }

            $resCat = Http::withToken($token)->get("{$this->apiUrl}/categorias");
            $rawCategorias = $resCat->successful() ? $resCat->json() : [];
            $categorias = isset($rawCategorias['data']) ? $rawCategorias['data'] : $rawCategorias;

            $resMat = Http::withToken($token)->get("{$this->apiUrl}/materiales");
            $rawMateriales = $resMat->successful() ? $resMat->json() : [];
            $materiales = isset($rawMateriales['data']) ? $rawMateriales['data'] : $rawMateriales;

        } catch (\Exception $e) {
            $error = 'Fallo de conexión con el servidor de inventario.';
            $categorias = [];
            $materiales = [];
        }

        return view('paginas.Inventario', [
            'stock' => $stock,
            'categorias' => $categorias,
            'materiales' => $materiales,
            'error' => $error,
            'filtros' => $params
        ]);
    }

    /**
     * EL PUENTE (Proxy): Descarga el reporte del Backend y lo entrega al usuario
     */
    public function exportar(Request $request, $formato)
    {
        $token = session('api_token');
        if (!$token) return abort(403);

        $endpoint = $formato === 'pdf' ? 'pdf' : 'csv';
        $params = $request->all();

        // Llamada de servidor a servidor enviando el TOKEN en el Header
        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->get("{$this->apiUrl}/reportes/inventario/{$endpoint}", $params);

        if ($response->successful()) {
            $extension = $formato === 'pdf' ? 'pdf' : 'xlsx';
            return response($response->body(), 200)
                ->header('Content-Type', $response->header('Content-Type'))
                ->header('Content-Disposition', 'attachment; filename="Inventario_Solare.' . $extension . '"');
        }

        return back()->with('error', 'No se pudo generar el reporte en el servidor central.');
            $stock = $response->json()['data'] ?? [];
            return view('paginas.Inventario', compact('stock'));
        }

        // Si el nodo de inventario no responde o rechaza el token
        return view('paginas.Inventario')->with('error', 'No se pudo sincronizar con el nodo de inventario central.');
    }

    /**
     * Actualización de stock con auditoría (Kardex)
     */
    public function updateStock(Request $request, $id)
    {
        $token = session('api_token');
        
        $request->validate([
            'tipo' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string|max:255'
        ]);

        $response = Http::withToken($token)->put("{$this->apiUrl}/inventario/{$id}", [
            'tipo' => $request->tipo,
            'cantidad' => $request->cantidad,
            'motivo' => $request->motivo
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Inventario actualizado correctamente.');
        }

        return back()->with('error', 'Error al actualizar stock.');
    }
}
