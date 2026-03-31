@extends('layouts.app')

@section('title', 'Clientes')
@section('label', 'Gestión de Clientes')
@section('header_title', 'Administración de Clientes')

@section('actions')
    <a href="{{ route('usuarios.create') }}"
        class="flex items-center justify-center text-white bg-solare-musgo hover:bg-opacity-90 focus:ring-4 focus:ring-solare-musgo/30 font-medium rounded-lg text-sm px-5 py-2.5 transition-all shadow-sm">
        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        Nuevo Cliente
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

            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-5 border-b border-gray-50">
                <div class="w-full md:w-1/2">
                    <label for="simple-search" class="sr-only">Buscar</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-solare-arcilla/60"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="simple-search" value="{{ $busqueda ?? '' }}"
                            class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-solare-musgo focus:border-solare-musgo block w-full pl-10 p-2.5 outline-none transition-all"
                            placeholder="Buscar por nombre, correo o ID...">
                    </div>
                </div>
            </div>

            @if($busqueda)
                <div class="px-5 py-3 text-[11px] font-bold tracking-widest text-solare-arcilla uppercase bg-gray-50/30 border-b border-gray-50">
                    {{ count($usuarios) }} resultado(s) para "{{ $busqueda }}"
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-[11px] font-bold tracking-widest text-solare-arcilla uppercase bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nombre completo</th>
                            <th class="px-6 py-4">Correo</th>
                            <th class="px-6 py-4 text-center">Rol</th>
                            <th class="px-6 py-4 text-center">Estado</th>
                            <th class="px-6 py-4">Registro</th>
                            <th class="px-6 py-4 text-right"><span class="sr-only">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($usuarios as $usuario)
                            @php
                                $u = (object) $usuario;
                            @endphp
                            @if($usuario['rol']['id'] == 3) 
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-medium text-gray-400">#{{ $u->id }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    {{ $u->nombre }} {{ $u->apellido_paterno ?? '' }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $u->correo }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-solare-arcilla/10 text-solare-arcilla text-[10px] font-bold uppercase rounded">
                                            {{ is_object($u->rol) ? $u->rol->nombre : ($u->rol['nombre']) }}
                                             
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>
                                        Activo
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">
                                    {{ $u->creado_en ?? 'Reciente' }}
                                </td>
                                <td class="px-6 py-4 text-right relative">
                                    <button onclick="toggleMenu('menu-{{ $u->id }}')"
                                        class="inline-flex items-center p-1.5 text-gray-400 hover:text-solare-musgo hover:bg-gray-100 rounded-lg transition-all focus:outline-none">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>

                                    <div id="menu-{{ $u->id }}"
                                        class="hidden absolute right-12 mt-0 w-44 bg-white rounded-xl shadow-xl z-50 border border-gray-100 py-1 overflow-hidden">
                                        <ul class="py-1 text-sm text-gray-700">
                                            <li>
                                                <a href="{{ route('usuarios.patch', $u->id) }}"
                                                    class="block w-full text-left px-4 py-2 text-solare-arcilla hover:bg-gray-50 transition-colors">
                                                    Ver detalles
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('usuarios.destroy', $u->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Seguro que deseas eliminar este registro?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="block w-full text-left px-4 py-2 text-red-500 hover:bg-red-50 transition-colors">
                                                        Eliminar
                                                    </button>
                                                    
                                                    @endif
                                                    
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                      
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    function toggleMenu(id) {
        document.querySelectorAll('[id^="menu-"]').forEach(el => {
            if (el.id !== id) el.classList.add('hidden');
        });
        document.getElementById(id).classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('button') && !e.target.closest('[id^="menu-"]')) {
            document.querySelectorAll('[id^="menu-"]').forEach(el => el.classList.add('hidden'));
        }
    });

    const searchInput = document.getElementById('simple-search');
    let debounceTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const valor = this.value.trim();
            const url = new URL(window.location.href);
            if (valor) {
                url.searchParams.set('busqueda', valor);
            } else {
                url.searchParams.delete('busqueda');
            }
            window.location.href = url.toString();
        }, 400);
    });
</script>

@endsection