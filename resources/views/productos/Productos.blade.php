@extends('layouts.app')

@section('label', 'Catálogo de Productos')
@section('header_title', 'Inventario General')

@section('actions')
    <a href="{{ route('productos.create') }}"
        class="flex items-center justify-center text-white bg-solare-musgo hover:bg-opacity-90 focus:ring-4 focus:ring-solare-musgo/30 font-medium rounded-lg text-sm px-5 py-2.5 transition-all shadow-sm">
        <svg class="h-4 w-4 mr-2" fill="currentColor" viewbox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
        </svg>
        Nuevo Producto
    </a>
@endsection

@section('content')
    <!-- Start block -->
    <section class="antialiased">
        <div class="mx-auto max-w-screen-xl">
            <!-- Start coding here -->
            <div class="bg-white relative shadow-sm border border-gray-100 sm:rounded-xl overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-5 border-b border-gray-50">
                    <div class="w-full md:w-1/2">
                        <form action="{{ route('productos.index') }}" method="GET" class="flex items-center">
                            <label for="simple-search" class="sr-only">Buscar</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-solare-arcilla/60"
                                        fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-8.485-8.486L2 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" name="busqueda" value="{{ $busqueda ?? '' }}"
                                    class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-solare-musgo focus:border-solare-musgo block w-full pl-10 p-2.5 outline-none transition-all"
                                    placeholder="Filtrar por nombre o SKU...">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-[11px] font-bold tracking-widest text-solare-arcilla uppercase bg-gray-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-4">ID</th>
                                <th scope="col" class="px-6 py-4">Imagen</th>
                                <th scope="col" class="px-6 py-4">Nombre del Producto</th>
                                <th scope="col" class="px-6 py-4">Categoría</th>
                                <th scope="col" class="px-6 py-4 text-right">Precio Base</th>
                                <th scope="col" class="px-6 py-4 text-right">Stock</th>
                                <th scope="col" class="px-6 py-4">SKU Base</th>
                                <th scope="col" class="px-6 py-4 text-center">Estado</th>
                                <th scope="col" class="px-6 py-4">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($productos as $producto)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-xs font-medium text-gray-400">#{{ $producto['id'] }}</td>

                                    <td class="px-6 py-4">
                                        @if (isset($producto['full_image_url']))
                                            <div class="w-12 h-12 rounded-lg border border-gray-100 overflow-hidden shadow-sm">
                                                <img class="w-full h-full object-cover"
                                                    src="{{ $producto['full_image_url'] }}"
                                                    alt="{{ $producto['nombre'] }}">
                                            </div>
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-gray-50 flex items-center justify-center border border-dashed border-gray-200">
                                                <span class="text-[10px] text-gray-400 uppercase">Sin imagen</span>
                                            </div>
                                        @endif
                                    </td>

                                    <th scope="row"
                                        class="px-6 py-4 font-semibold text-gray-800 whitespace-nowrap">
                                        {{ $producto['nombre'] }}</th>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-solare-arcilla/10 text-solare-arcilla text-[10px] font-bold uppercase rounded">
                                            {{ $producto['categoria']['nombre'] ?? 'Sin Categoría' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-gray-900">
                                        ${{ number_format($producto['precio_base'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-gray-900">
    {{ $producto['stock'] ?? '—' }}
</td>
                                    <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $producto['sku_base'] }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($producto['activo'])
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>
                                                Activo
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span>
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right relative">
                                        <button id="btn-{{ $producto['id'] }}"
                                            onclick="toggleMenu('menu-{{ $producto['id'] }}')"
                                            class="inline-flex items-center p-1.5 text-gray-400 hover:text-solare-musgo hover:bg-gray-100 rounded-lg transition-all focus:outline-none"
                                            type="button">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>

                                        <div id="menu-{{ $producto['id'] }}"
                                            class="hidden absolute right-12 mt-0 w-44 bg-white rounded-xl shadow-xl z-50 border border-gray-100 py-1 overflow-hidden">
                                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                                <li>
                                                    <form action="{{ route('productos.toggleEstatus', $producto['id']) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        <button type="submit"
                                                            class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                            {{ $producto['activo'] ? 'Desactivar' : 'Activar' }}
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <a href="{{ route('productos.imagenes', $producto['id']) }}"
                                                        class="block w-full text-left px-4 py-2 text-blue-500 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                        Galería
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('productos.edit', $producto['id']) }}"
                                                        class="block w-full text-left px-4 py-2 text-amber-400 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                        Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('productos.destroy', $producto['id']) }}"
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
        function toggleMenu(id) {
            const menu = document.getElementById(id);
            const allMenus = document.querySelectorAll('[id^="menu-"]');
            allMenus.forEach(m => {
                if (m.id !== id) m.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
        }

        // Cerrar menús al hacer clic fuera
        window.addEventListener('click', function(e) {
            if (!e.target.closest('button[id^="btn-"]') && !e.target.closest('[id^="menu-"]')) {
                document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>
@endsection