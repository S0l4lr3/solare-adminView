<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');

        $usuarios = Usuario::with('rol')
            ->when($busqueda, function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where('nombre', 'LIKE', "%{$busqueda}%")
                      ->orWhere('apellido_paterno', 'LIKE', "%{$busqueda}%")
                      ->orWhere('apellido_materno', 'LIKE', "%{$busqueda}%")
                      ->orWhere('correo', 'LIKE', "%{$busqueda}%");

                    if (is_numeric($busqueda)) {
                        $q->orWhere('id', $busqueda);
                    }
                });
            })
            ->get();

        return view('/empleados/usuarios', compact('usuarios', 'busqueda'));
    }

    public function create()
    {
        $roles = Rol::all();
        return view('/empleados/usuarios_crear', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'correo'           => 'required|email|unique:usuarios,correo',
            'contrasena'       => 'required|string|min:8|confirmed',
            'rol_id'           => 'required|exists:roles,id',
        ]);

        Usuario::create([
            'nombre'           => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'correo'           => $request->correo,
            'contrasena'       => Hash::make($request->contrasena),
            'rol_id'           => $request->rol_id,
        ]);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Empleado creado correctamente.');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $roles   = Rol::all();
        return view('/empleados/usuarios_editar', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre'           => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'correo'           => 'required|email|unique:usuarios,correo,' . $id,
            'contrasena'       => 'nullable|string|min:8|confirmed',
            'rol_id'           => 'required|exists:roles,id',
        ]);

        $datos = [
            'nombre'           => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'correo'           => $request->correo,
            'rol_id'           => $request->rol_id,
        ];

        // Solo actualiza contraseña si el usuario escribió una nueva
        if ($request->filled('contrasena')) {
            $datos['contrasena'] = Hash::make($request->contrasena);
        }

        $usuario->update($datos);

        return redirect()->route('usuarios.index');
    }

    public function destroy($id)
    {
        Usuario::findOrFail($id)->delete();
        return redirect()->route('usuarios.index');
    }
}