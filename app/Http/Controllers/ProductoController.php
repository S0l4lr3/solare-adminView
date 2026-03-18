<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductoController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'https://solare-backend-production.up.railway.app/api');
    }

    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $token = session('api_token'); // OBTENEMOS EL TOKEN DE LA SESIÓN

        // Enviamos el token con Http::withToken()
        $response = Http::withToken($token)->get("{$this->apiUrl}/productos", [
            'search' => $busqueda
        ]);

        $productos = $response->successful() ? $response->json() : [];

        $categoriasResponse = Http::withToken($token)->get("{$this->apiUrl}/categorias");
        $categorias = $categoriasResponse->successful() ? $categoriasResponse->json() : [];

        return view('productos/Productos', compact('productos', 'categorias', 'busqueda'));
    }

    public function create()
    {
        $token = session('api_token');
        $categoriasResponse = Http::withToken($token)->get("{$this->apiUrl}/categorias");
        $categorias = $categoriasResponse->successful() ? $categoriasResponse->json() : [];
        return view('productos/Productos-crear', compact('categorias'));
    }

    public function store(Request $request)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->post("{$this->apiUrl}/productos", $request->all());

        if ($response->successful()) {
            return redirect()->route('productos.index')->with('success', 'Producto guardado.');
        }

        return back()->with('error', 'Error al guardar en el servidor: ' . $response->body());
    }

    public function edit($id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->get("{$this->apiUrl}/productos/{$id}");
        $producto = $response->successful() ? $response->json() : null;

        $categoriasResponse = Http::withToken($token)->get("{$this->apiUrl}/categorias");
        $categorias = $categoriasResponse->successful() ? $categoriasResponse->json() : [];

        return view('productos/Productos-editar', compact('producto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->put("{$this->apiUrl}/productos/{$id}", $request->all());

        if ($response->successful()) {
            return redirect()->route('productos.index')->with('success', 'Producto actualizado.');
        }

        return back()->with('error', 'Error al actualizar.');
    }

    public function toggleEstatus($id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->patch("{$this->apiUrl}/productos/{$id}/toggle-estatus");

        if ($response->successful()) {
            return back()->with('success', 'Estado del producto actualizado.');
        }

        return back()->with('error', 'No se pudo cambiar el estado.');
    }

    public function destroy($id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->delete("{$this->apiUrl}/productos/{$id}");
        return redirect()->route('productos.index');
    }
}