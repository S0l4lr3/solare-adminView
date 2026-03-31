<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class materialesController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://localhost:8000/api');
    }

    /**
     * Listado de materiales.
     */
    public function index()
    {
        $token = session('api_token');
        $response = Http::withToken($token)->get("{$this->apiUrl}/materiales");
        
        $res = $response->json();

        // Según tu dd, los materiales están en $res['data']
        if (isset($res['data'])) {
            $materiales = $res['data'];
        } else {
            $materiales = $res; // Por si acaso el backend cambia
        }

        // Si no es un array, mandamos uno vacío para que el Blade no falle
        if (!is_array($materiales)) {
            $materiales = [];
        }

        return view('materiales.index', compact('materiales'));
    }

    public function create()
    {
        return view('materiales.create');
    }

    public function store(Request $request)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->post("{$this->apiUrl}/materiales", $request->all());

        if ($response->successful()) {
            return redirect()->route('materiales.index')->with('success', 'Material guardado.');
        }

        return back()->withInput()->with('error', 'Error al guardar.');
    }

    /**
     * Obtener material para editar.
     */
    public function edit(Request $request)
    {
        $token = session('api_token');
        $id = $request->id;
        $response = Http::withToken($token)->get("{$this->apiUrl}/materiales/{$id}");
        $res = $response->json();
        

        $materiales = $res['data'];
        $material = $materiales;

        return view('materiales.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->put("{$this->apiUrl}/materiales/{$id}", $request->all());

        if ($response->successful()) {
            return redirect()->route('materiales.index')->with('success', 'Material actualizado.');
        }

        return back()->withInput()->with('error', 'Error al actualizar.');
    }

    public function destroy($id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->delete("{$this->apiUrl}/materiales/{$id}");

        if ($response->successful()) {
            return redirect()->route('materiales.index')->with('success', 'Material eliminado.');
        }

        return back()->with('error', 'No se pudo eliminar.');
    }
}
