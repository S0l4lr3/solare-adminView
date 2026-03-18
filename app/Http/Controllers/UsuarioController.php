<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UsuarioController extends Controller
{
    protected $apiUrl = 'https://solare-backend-production.up.railway.app/api';

    public function index(Request $request)
    {
        // LÍNEAS 12-17: LÓGICA DE BÚSQUEDA Y FILTRO DE ROL
        $busqueda = $request->input('busqueda');
        $rol = $request->input('rol');

        $response = Http::get("{$this->apiUrl}/admin/usuarios", [
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
        $response = Http::post("{$this->apiUrl}/admin/usuarios", $request->all());

        if ($response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario guardado.');
        }

        return back()->with('error', 'Error al guardar el usuario en el backend.');
    }

    public function edit($id)
    {
        $response = Http::get("{$this->apiUrl}/admin/usuarios/{$id}");
        $usuario = $response->successful() ? $response->json() : null;

        return view('empleados/usuarios_editar', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $response = Http::put("{$this->apiUrl}/admin/usuarios/{$id}", $request->all());

        if ($response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
        }

        return back()->with('error', 'Error al actualizar.');
    }

    // LÍNEAS 72-77: MÉTODO PARA CAMBIAR EL ESTATUS DEL USUARIO
    public function toggleEstatus($id)
    {
        $response = Http::patch("{$this->apiUrl}/admin/usuarios/{$id}/toggle-estatus");

        if ($response->successful()) {
            return back()->with('success', 'Estado del usuario actualizado correctamente.');
        }

        return back()->with('error', 'No se pudo cambiar el estado del usuario.');
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->apiUrl}/admin/usuarios/{$id}");
        return redirect()->route('usuarios.index');
    }
}