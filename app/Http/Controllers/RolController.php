<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RolController extends Controller
{
    protected $apiUrl = 'http://127.0.0.1:8000/api';

    public function index()
    {
        $token = session('api_token');
        $response = Http::withToken($token)->get("{$this->apiUrl}/roles");
        
        $roles = $response->successful() ? ($response->json()['data'] ?? []) : [];
        
        return view('roles.roles', compact('roles'));
    }

    public function create()
    {
        return view('roles.roles-crear');
    }

    public function store(Request $request)
    {
        $token = session('api_token');
        
        $request->validate([
            'nombre' => 'required|string|max:100|unique:roles,nombre'
        ]);

        $response = Http::withToken($token)->post("{$this->apiUrl}/roles", [
            'nombre' => $request->nombre
        ]);

        if ($response->successful()) {
            return redirect()->route('roles.index')->with('success', 'Nuevo rol jerárquico registrado.');
        }

        return back()->with('error', 'Error al crear el rol en el servidor central.');
    }

    public function destroy($id)
    {
        $token = session('api_token');
        Http::withToken($token)->delete("{$this->apiUrl}/roles/{$id}");
        return back()->with('success', 'Rol eliminado correctamente.');
    }
}
