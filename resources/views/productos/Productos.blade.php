@extends('layouts.app')

@section('title', 'productos')
@section('label', 'Panel General')
@section('header_title', 'Bienvenido, Admin')


@section('content')

    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->

            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <div class="w-full md:w-1/2">
                            <label for="simple-search" class="sr-only">Buscar</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" value="{{ $busqueda ?? '' }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Buscar por nombre, SKU, categoría o ID...">
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <a href="{{ route('productos.create') }}"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 whitespace-nowrap">
                            + Nuevo producto
                        </a>
                    </div>
                </div>
                @if ($busqueda)
                    <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                        {{ $productos->count() }} resultado(s) para
                        <span class="font-semibold text-gray-700 dark:text-gray-200">"{{ $busqueda }}"</span>
                    </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">ID</th>
                                <th scope="col" class="px-4 py-3">imagen principal</th>
                                <th scope="col" class="px-4 py-3">categoría</th>
                                <th scope="col" class="px-4 py-3">Nombre</th>
                                <th scope="col" class="px-4 py-3">Descripcion</th>
                                <th scope="col" class="px-4 py-3">Precio base</th>
                                <th scope="col" class="px-4 py-3">Cantidades disponibles</th>
                                <th scope="col" class="px-4 py-3">Estatus</th>
                                <th scope="col" class="px-4 py-3">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $producto)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3">{{ $producto->id }}</td>

                                    <td class="px-4 py-3">
                                        @if ($producto->imagenPrincipal)
                                            <img class="w-12 h-10 rounded object-cover"
                                                src="{{ asset('storage/' . $producto->imagenPrincipal->url) }}"
                                                alt="{{ $producto->nombre }}">
                                        @else
                                            <span class="text-gray-400 text-xs">Sin imagen</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">{{ $producto->categoria->nombre ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $producto->nombre }}</td>
                                    <td class="px-4 py-3">{{ Str::limit($producto->descripcion, 50) }}</td>
                                    <td class="px-4 py-3">${{ number_format($producto->precio_base, 2) }}</td>
                                    <td class="px-4 py-3">{{ $producto->sku_base ?? '—' }}</td>

                                    <td class="px-4 py-3">
                                        @if ($producto->activo == 1)
                                            <span class="text-green-500 font-semibold">Activo</span>
                                        @else
                                            <span class="text-red-500 font-semibold">Inactivo</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-right relative">
                                        <button onclick="toggleMenu('menu-{{ $producto->id }}')"
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-gray-500 hover:text-gray-800 rounded-lg">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>

                                        <div id="menu-{{ $producto->id }}"
                                            class="hidden absolute right-8 mt-1 w-44 bg-white rounded-lg shadow-lg z-50 dark:bg-gray-700">
                                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">

                                                {{-- Toggle estatus --}}
                                                <li>
                                                    <form action="{{ route('productos.toggleEstatus', $producto->id) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        <button type="submit"
                                                            class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                            {{ $producto->activo ? 'Desactivar' : 'Activar' }}
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <a href="{{ route('productos.edit', $producto->id) }}"
                                                        class="block w-full text-left px-4 py-2 text-amber-400 hover:bg-gray-100 dark:hover:bg-gray-600 text-sm">
                                                        Editar
                                                    </a>
                                                </li>
                                                {{-- Eliminar --}}
                                                <li>
                                                    <form action="{{ route('productos.destroy', $producto->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
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
        // ============================================
        // MENÚS DROPDOWN
        // ============================================
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

        // ============================================
        // FILTRO EN TIEMPO REAL
        // ============================================
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
