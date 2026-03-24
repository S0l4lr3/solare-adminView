@extends('layouts.app')

@section('title', 'Pedidos')
@section('label', 'Panel General')
@section('header_title', 'Bienvenido, Admin')

@section('content')

@php
    // Helpers de color reutilizables
    function badgePago($estado) {
        return match($estado) {
            'pagado'      => 'bg-green-100 text-green-700',
            'pendiente'   => 'bg-yellow-100 text-yellow-700',
            'fallido'     => 'bg-red-100 text-red-600',
            'reembolsado' => 'bg-purple-100 text-purple-700',
            default       => 'bg-gray-100 text-gray-500',
        };
    }
    function badgeEnvio($estado) {
        return match($estado) {
            'entregado'  => 'bg-green-100 text-green-700',
            'enviado'    => 'bg-blue-100 text-blue-700',
            'preparando' => 'bg-yellow-100 text-yellow-700',
            'pendiente'  => 'bg-gray-100 text-gray-500',
            'cancelado'  => 'bg-red-100 text-red-600',
            default      => 'bg-gray-100 text-gray-500',
        };
    }
@endphp

<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

            {{-- Barra superior: buscador + filtros --}}
            <div class="flex flex-col md:flex-row items-center justify-between gap-3 p-4">

                {{-- Buscador --}}
                <div class="w-full md:w-1/3">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="simple-search" value="{{ $busqueda ?? '' }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Buscar por ID o cliente...">
                    </div>
                </div>

                {{-- Filtros de estado --}}
                <div class="flex flex-wrap gap-2 w-full md:w-auto">
                    <select id="filtro-pago"
                        class="text-sm border border-gray-300 rounded-lg p-2 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Estado de pago</option>
                        @foreach(['pendiente','pagado','fallido','reembolsado'] as $ep)
                            <option value="{{ $ep }}" {{ ($estadoPago ?? '') === $ep ? 'selected' : '' }}>
                                {{ ucfirst($ep) }}
                            </option>
                        @endforeach
                    </select>

                    <select id="filtro-envio"
                        class="text-sm border border-gray-300 rounded-lg p-2 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Estado de envío</option>
                        @foreach(['pendiente','preparando','enviado','entregado','cancelado'] as $ee)
                            <option value="{{ $ee }}" {{ ($estadoEnvio ?? '') === $ee ? 'selected' : '' }}>
                                {{ ucfirst($ee) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- Contador de resultados --}}
            @if($busqueda || $estadoPago || $estadoEnvio)
                <div class="px-4 py-2 text-sm text-gray-500 border-b dark:border-gray-700">
                    {{ $pedidos->count() }} resultado(s) encontrados
                </div>
            @endif

            {{-- Tabla --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Cliente</th>
                            <th class="px-4 py-3">Dirección de envío</th>
                            <th class="px-4 py-3">Fecha pedido</th>
                            <th class="px-4 py-3">Estado pago</th>
                            <th class="px-4 py-3">Estado envío</th>
                            <th class="px-4 py-3">Notas</th>
                            <th class="px-4 py-3">Creado</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $pedido)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3 font-bold text-gray-700 dark:text-white">
                                    #{{ $pedido->id }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $pedido->cliente->nombre_completo ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    @if($pedido->direccionEnvio)
                                        <span title="{{ $pedido->direccionEnvio->direccion_completa }}">
                                            {{ $pedido->direccionEnvio->alias }} —
                                            {{ Str::limit($pedido->direccionEnvio->direccion_completa, 40) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    {{ $pedido->fecha_pedido
                                        ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y')
                                        : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ badgePago($pedido->estado_pago) }}">
                                        {{ ucfirst($pedido->estado_pago ?? '—') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ badgeEnvio($pedido->estado_envio) }}">
                                        {{ ucfirst($pedido->estado_envio ?? '—') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    {{ Str::limit($pedido->notas, 40) ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    {{ \Carbon\Carbon::parse($pedido->creado_en)->format('d/m/Y H:i') }}
                                </td>

                                {{-- Acciones --}}
                                <td class="px-4 py-3 text-right relative">
                                    <button onclick="toggleMenu('menu-{{ $pedido->id }}')"
                                        class="inline-flex items-center p-0.5 text-sm font-medium text-gray-500 hover:text-gray-800 rounded-lg">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>

                                    <div id="menu-{{ $pedido->id }}"
                                        class="hidden absolute right-8 mt-1 w-56 bg-white rounded-lg shadow-lg z-50 dark:bg-gray-700">
                                        <div class="p-3 border-b dark:border-gray-600">

                                            {{-- Cambiar estado de pago --}}
                                            <form action="{{ route('pedidos.updateEstado', $pedido->id) }}" method="POST" class="mb-2">
                                                @csrf @method('PATCH')
                                                <label class="block text-xs text-gray-500 mb-1">Estado de pago</label>
                                                <select name="estado_pago" onchange="this.form.submit()"
                                                    class="w-full text-xs border border-gray-300 rounded p-1 dark:bg-gray-600 dark:text-white">
                                                    @foreach(['pendiente','pagado','fallido','reembolsado'] as $ep)
                                                        <option value="{{ $ep }}" {{ $pedido->estado_pago === $ep ? 'selected' : '' }}>
                                                            {{ ucfirst($ep) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>

                                            {{-- Cambiar estado de envío --}}
                                            <form action="{{ route('pedidos.updateEstado', $pedido->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <label class="block text-xs text-gray-500 mb-1">Estado de envío</label>
                                                <select name="estado_envio" onchange="this.form.submit()"
                                                    class="w-full text-xs border border-gray-300 rounded p-1 dark:bg-gray-600 dark:text-white">
                                                    @foreach(['pendiente','preparando','enviado','entregado','cancelado'] as $ee)
                                                        <option value="{{ $ee }}" {{ $pedido->estado_envio === $ee ? 'selected' : '' }}>
                                                            {{ ucfirst($ee) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </div>

                                        {{-- Eliminar --}}
                                        <div class="p-1">
                                            <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST"
                                                onsubmit="return confirm('¿Seguro que deseas eliminar este pedido?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="block w-full text-left px-3 py-2 text-xs text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600 rounded">
                                                    Eliminar pedido
                                                </button>
                                            </form>
                                        </div>
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

    // Buscador en tiempo real
    const searchInput = document.getElementById('simple-search');
    let debounceTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const url = new URL(window.location.href);
            const valor = this.value.trim();
            if (valor) {
                url.searchParams.set('busqueda', valor);
            } else {
                url.searchParams.delete('busqueda');
            }
            window.location.href = url.toString();
        }, 400);
    });

    // Filtros de estado en tiempo real
    document.getElementById('filtro-pago').addEventListener('change', function() {
        const url = new URL(window.location.href);
        if (this.value) {
            url.searchParams.set('estado_pago', this.value);
        } else {
            url.searchParams.delete('estado_pago');
        }
        window.location.href = url.toString();
    });

    document.getElementById('filtro-envio').addEventListener('change', function() {
        const url = new URL(window.location.href);
        if (this.value) {
            url.searchParams.set('estado_envio', this.value);
        } else {
            url.searchParams.delete('estado_envio');
        }
        window.location.href = url.toString();
    });
</script>

@endsection