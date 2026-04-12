<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductoController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL', '127.0.0.1:8000/api');
    }

    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $token = session('api_token');
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
        $pendingRequest = Http::withToken($token)->acceptJson();

        if ($request->hasFile('imagen')) {
            $pendingRequest = $pendingRequest->attach(
                'imagen',
                file_get_contents($request->file('imagen')->getRealPath()),
                $request->file('imagen')->getClientOriginalName()
            );
        }

        $response = $pendingRequest->post(
            "{$this->apiUrl}/productos",
            $request->except(['imagen', '_token'])
        );

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
        $pendingRequest = Http::withToken($token)->acceptJson();

        if ($request->hasFile('imagen')) {
            $pendingRequest = $pendingRequest->attach(
                'imagen',
                file_get_contents($request->file('imagen')->getRealPath()),
                $request->file('imagen')->getClientOriginalName()
            );
        }

        // CÓDIGO CORREGIDO (Front-end)
        $datosAEnviar = $request->except(['imagen', '_token', '_method']);
        $datosAEnviar['_method'] = 'PUT'; // <- Añadimos manualmente el método PUT para que la API lo entienda

        $response = $pendingRequest->post(
            "{$this->apiUrl}/productos/{$id}",
            $datosAEnviar
        );

        if ($response->successful()) {
            return redirect()->route('productos.index')->with('success', 'Producto actualizado.');
        }

        return back()->with('error', 'Error al actualizar: ' . $response->body());
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

    // public function gestionarImagenes($id)
    // {
    //     $token = session('api_token');

    //     // Datos del producto
    //     $resProd = Http::withToken($token)->get("{$this->apiUrl}/productos/{$id}");
    //     $producto = $resProd->successful() ? $resProd->json() : null;

    //     // Lista de imágenes desde el nuevo endpoint
    //     $resImg = Http::withToken($token)->get("{$this->apiUrl}/productos/{$id}/imagenes");
    //     dd($resImg->json());
    //     $imagenes = $resImg->successful() ? $resImg->json()['data'] : [];

    //     return view('productos/Imagenes-gestionar', compact('producto', 'imagenes'));
    // }

    public function gestionarImagenes($id)
    {
        $token = session('api_token');

        $resProd = Http::withToken($token)->get("{$this->apiUrl}/productos/{$id}");
        $producto = $resProd->successful() ? $resProd->json() : null;

        $resImg = Http::withToken($token)->get("{$this->apiUrl}/productos/{$id}/imagenes");
        $imagenes = $resImg->successful() ? $resImg->json()['data'] : [];

        // Construye la URL completa de cada imagen
        $imagenes = array_map(function ($img) {
            $img['full_image_url'] = asset('storage/' . $img['url']);
            return $img;
        }, $imagenes);

        return view('productos/Imagenes-gestionar', compact('producto', 'imagenes'));
    }

    public function subirImagenes(Request $request, $id)
    {
        $token = session('api_token');
        $pendingRequest = Http::withToken($token)->acceptJson();

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $file) {
                $pendingRequest->attach(
                    "imagenes[$index]",
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }
        }

        $pendingRequest->post("{$this->apiUrl}/productos/{$id}/imagenes", [
            'producto_id' => $id,
            'es_principal' => $request->has('es_principal') ? 1 : 0
        ]);

        return back()->with('success', 'Imágenes subidas correctamente.');
    }

    public function eliminarImagen($id)
    {
        $token = session('api_token');
        Http::withToken($token)->delete("{$this->apiUrl}/imagenes/{$id}");
        return back()->with('success', 'Imagen eliminada.');
    }

    public function marcarPrincipal($id)
    {
        $token = session('api_token');
        Http::withToken($token)->patch("{$this->apiUrl}/imagenes/{$id}/principal");
        return back()->with('success', 'Nueva imagen principal establecida.');
    }
}