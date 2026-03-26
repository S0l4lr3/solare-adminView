<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UsuarioController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL', '127.0.0.1:8000/api');
    }

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

    //usuarios por ID
    public function show($id)
    {
        $token = session('api_token');
        $response = Http::withToken($token)->get("{$this->apiUrl}/admin/usuarios/{$id}");
        $usuario = $response->successful() ? $response->json() : null;

        return view('empleados/usuarios_editar', compact('usuario'));
    }

    public function create()
    {
        $token = session('api_token');
        
        // Consultamos los roles en el backend
        $response = Http::withToken($token)->get("{$this->apiUrl}/admin/roles");
        
        // Laravel a veces devuelve el JSON directamente o bajo una llave 'data'
        $roles = $response->json();
        if (isset($roles['data'])) {
            $roles = $roles['data'];
        }

        // Si la respuesta falló o está vacía, usamos roles de respaldo basados en tu BD
        if (empty($roles)) {
            $roles = [
                ['id' => 1, 'nombre' => 'CEO'],
                ['id' => 2, 'nombre' => 'Administrador'],
                ['id' => 3, 'nombre' => 'Gerente'],
                ['id' => 4, 'nombre' => 'Supervisor'],
                ['id' => 5, 'nombre' => 'Vendedor'],
                ['id' => 6, 'nombre' => 'Almacenista'],
            ];
        }
        //dd($roles);
        return view('empleados/usuarios_crear', compact('roles'));
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


    public function update(Request $request, $id)
    {
        $token = session('api_token');
        
        $datosLimpios = $request->except('_token', '_method', 'contrasena_confirmation');
        //  @dd($datosLimpios);
        $response = Http::withToken($token)->patch("{$this->apiUrl}/admin/usuarios/{$id}", $datosLimpios);
        
        if (!$response->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
        }
        if($response->status() === 422){
            return back()->with('error', 'Error al actualizar.',$response->json()['errors'] ?? []);
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