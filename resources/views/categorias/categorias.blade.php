@extends('layouts.app')

@section('title', 'Categorías')
@section('label', 'Panel General')
@section('header_title', 'Bienvenido, Admin')

@section('content')

    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">

            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                {{-- Mensajes de éxito / error --}}
                @if (session('success'))
                    <div class="mx-4 mt-4 p-4 bg-green-100 text-green-700 text-sm rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mx-4 mt-4 p-4 bg-red-100 text-red-700 text-sm rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <label for="simple-search" class="sr-only">Buscar</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="simple-search" value="{{ $busqueda ?? '' }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Buscar por nombre, descripción o ID...">
                        </div>
                    </div>
                    <div class="w-full md:w-auto flex items-center justify-end">
                        <a href="{{ route('categorias.create') }}"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 whitespace-nowrap">
                            + Nueva categoría
                        </a>
                    </div>
                </div>

                @if ($busqueda)
                    <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                        {{ $categorias->count() }} resultado(s) para
                        <span class="font-semibold text-gray-700 dark:text-gray-200">"{{ $busqueda }}"</span>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Nombre</th>
                                <th class="px-4 py-3">Descripción</th>
                                <th class="px-4 py-3">Productos</th>
                                <th class="px-4 py-3">Creado</th>
                                <th class="px-4 py-3">Actualizado</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categorias as $categoria)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3">{{ $categoria["id"] }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                        {{ $categoria["nombre"] }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ Str::limit($categoria["descripcion"], 60) ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ $categoria["productos_count"] ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        {{ isset($categoria['creado_en']) ? \Carbon\Carbon::parse($categoria['creado_en'])->format('d/m/Y') : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        {{ isset($categoria['actualizado_en']) ? \Carbon\Carbon::parse($categoria['actualizado_en'])->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right relative">
                                        <button onclick="toggleMenu('menu-{{ $categoria["id"] }}')"
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-gray-500 hover:text-gray-800 rounded-lg">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>

                                        <div id="menu-{{ $categoria["id"] }}"
                                            class="hidden absolute right-8 mt-1 w-44 bg-white rounded-lg shadow-lg z-50 dark:bg-gray-700">
                                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                                <li>
                                                    <a href="{{ route('categorias.edit', $categoria["id"]) }}"
                                                        class="block w-full text-left px-4 py-2 text-amber-400 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                        Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('categorias.destroy', $categoria["id"]) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="block w-full text-left px-4 py-2 text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                            Borrar
                                                        </button>
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
