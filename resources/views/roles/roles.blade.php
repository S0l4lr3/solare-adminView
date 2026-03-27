@extends('layouts.app')

@section('title', 'Roles')
@section('label', 'Gestión del Roles')
@section('header_title', 'Administración de Roles')

@section('actions')
    <a href="{{ route('roles.create') }}"
        class="flex items-center justify-center text-white bg-solare-musgo hover:bg-opacity-90 focus:ring-4 focus:ring-solare-musgo/30 font-medium rounded-lg text-sm px-5 py-2.5 transition-all shadow-sm">
        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nuevo Rol
    </a>
@endsection

@section('content')
<section class="antialiased">
    <div class="mx-auto max-w-screen-xl">
        <div class="bg-white relative shadow-sm border border-gray-100 sm:rounded-xl overflow-hidden">

            @if(session('success'))
                <div class="mx-5 mt-5 p-4 bg-green-50 border border-green-100 text-green-700 text-sm rounded-xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto mt-4">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-[11px] font-bold tracking-widest text-solare-arcilla uppercase bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nombre del Rol</th>
                            <th class="px-6 py-4">Descripción</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @if(!empty($roles))
                            @foreach ($roles as $rol)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-xs font-medium text-gray-400">#{{ $rol['id'] }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $rol['nombre'] }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $rol['descripcion'] }}</td>
                                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                                        <a href="{{ route('roles.edit', $rol['id']) }}" class="text-solare-musgo hover:text-opacity-80 font-medium text-sm">Editar</a>
                                        <form action="{{ route('roles.destroy', $rol['id']) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este rol?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm ml-3">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay roles registrados.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection