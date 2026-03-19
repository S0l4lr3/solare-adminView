@extends('layouts.app')

@section('title', 'Nuevo Empleado')
@section('label', 'Administración de Personal')
@section('header_title', 'Registrar Nuevo Empleado')

@section('content')

<section class="bg-[#f7f5f2]">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16 bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
        <h2 class="serif mb-6 text-3xl font-normal text-gray-900 border-b pb-4">Datos de la Cuenta</h2>

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

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
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        placeholder="Ej. Juan Carlos" required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Apellido paterno</label>
                    <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        placeholder="Ej. García" required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Apellido materno</label>
                    <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        placeholder="Ej. López">
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Correo electrónico</label>
                    <input type="email" name="correo" value="{{ old('correo') }}"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        placeholder="correo@ejemplo.com" required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Contraseña</label>
                    <input type="password" name="contrasena"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        placeholder="Mínimo 8 caracteres" required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Confirmar contraseña</label>
                    <input type="password" name="contrasena_confirmation"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        placeholder="Repite la contraseña" required>
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Rol</label>
                    <select name="rol_id"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none">
                        <option value="">Selecciona un rol</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol['id'] }}">{{ $rol['nombre'] }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="POST"
                    class="px-6 py-2.5 text-sm font-medium text-white bg-solare-musgo rounded-lg hover:bg-opacity-90 transition-all shadow-md">
                    Guardar empleado
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