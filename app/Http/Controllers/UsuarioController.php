<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UsuarioController extends Controller
{
    protected $apiUrl = 'https://solare-backend-production.up.railway.app/api';

    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $rol = $request->input('rol');
        $token = session('api_token'); // OBTENEMOS EL TOKEN DE SEGURIDAD

        // El backend tiene la gestión de usuarios bajo el prefijo 'admin'
        $response = Http::withToken($token)->get("{$this->apiUrl}/admin/usuarios", [
            'busqueda' => $busqueda,
            'rol' => $rol
        ]);

        $usuarios = $response->successful() ? $response->json() : [];

        return view('empleados/usuarios', compact('usuarios', 'busqueda', 'rol'));
    }

    public function create()
    {
        return view('empleados/usuarios_crear');
    }

    public function store(Request $request)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->post("{$this->apiUrl}/admin/usuarios", $request->all());

        if ($response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario guardado.');
        }

        return back()->with('error', 'Error al guardar el usuario en el backend: ' . $response->body());
    }

    public function edit($id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->get("{$this->apiUrl}/admin/usuarios/{$id}");
        $usuario = $response->successful() ? $response->json() : null;

        return view('empleados/usuarios_editar', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->put("{$this->apiUrl}/admin/usuarios/{$id}", $request->all());

        if ($response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
        }

        return back()->with('error', 'Error al actualizar.');
    }

    public function toggleEstatus($id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->patch("{$this->apiUrl}/admin/usuarios/{$id}/toggle-estatus");

        if ($response->successful()) {
            return back()->with('success', 'Estado del usuario actualizado correctamente.');
        }

        return back()->with('error', 'No se pudo cambiar el estado del usuario.');
    }

    public function destroy($id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->delete("{$this->apiUrl}/admin/usuarios/{$id}");
        
        if ($response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
        }

        return back()->with('error', 'Error al eliminar.');
    }
}