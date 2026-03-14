<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function Formulario()
    {
        return view("Auth.Login");
    }

    public function Registro()
    {
        return view("Auth.Registro");
    }

    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Handshake con el Nodo Backend de Solare
        $response = Http::post('https://solare-backend-production.up.railway.app/api/login', [
            'correo' => $request->email,
            'contrasena' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            // Capa de Sesión: Almacenamos el token de Sanctum
            session(['api_token' => $data['data']['token']]);
            session(['user_data' => $data['data']['user']]);

            // Redirigir al dashboard administrativo de Solare
            return redirect()->route('dashboard')->with('success', 'Nodo administrativo autenticado.');
        }

        return back()->withErrors(['email' => 'Acceso denegado: Revisa tus credenciales en el sistema central.']);
    }

    public function Logout()
    {
        $token = session('api_token');
        if ($token) {
            Http::withToken($token)->post('https://solare-backend-production.up.railway.app/api/logout');
        }

        Session::forget(['api_token', 'user_data']);
        return redirect()->route('login');
    }
}
