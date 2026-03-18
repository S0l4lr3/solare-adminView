@extends('layouts.app')

@section('title', 'Editar Empleado')
@section('label', 'Administración de Personal')
@section('header_title', 'Modificar Datos de Empleado')

@section('content')

<section class="bg-[#f7f5f2]">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16 bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
        <h2 class="serif mb-6 text-3xl font-normal text-gray-900 border-b pb-4">Editar Información</h2>

        <form action="{{ route('usuarios.update', $usuario['id'] ?? $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 rounded-lg">
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Nombre(s)</label>
                    <input type="text" name="nombre" value="{{ $usuario['nombre'] ?? $usuario->nombre }}"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Apellido paterno</label>
                    <input type="text" name="apellido_paterno" value="{{ $usuario['apellido_paterno'] ?? $usuario->apellido_paterno }}"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Apellido materno</label>
                    <input type="text" name="apellido_materno" value="{{ $usuario['apellido_materno'] ?? $usuario->apellido_materno }}"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Correo electrónico</label>
                    <input type="email" name="correo" value="{{ $usuario['correo'] ?? $usuario->correo }}"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Nueva contraseña
                        <span class="text-gray-400 font-normal">(dejar vacío para no cambiar)</span>
                    </label>
                    <input type="password" name="contrasena"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        placeholder="Mínimo 8 caracteres">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Confirmar contraseña</label>
                    <input type="password" name="contrasena_confirmation"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        placeholder="Repite la contraseña">
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Rol</label>
                    <select name="rol_id"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none">
                        <option value="">Selecciona un rol</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol['id'] ?? '' }}" {{ (($usuario['rol_id'] ?? $usuario->rol_id) == ($rol['id'] ?? '')) ? 'selected' : '' }}>
                                {{ $rol['nombre'] ?? 'Sin nombre' }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-medium text-white bg-solare-musgo rounded-lg hover:bg-opacity-90 transition-all shadow-md">
                    Guardar Cambios
                </button>
                <a href="{{ route('usuarios.index') }}"
                    class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</section>

@endsection