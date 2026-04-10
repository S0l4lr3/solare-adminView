@extends('layouts.app')

@section('label', 'Catálogo de pedidos')
@section('header_title', 'Pedidos generales')

@section('content')
    <section class="antialiased">
        <div class="mx-auto max-w-screen-xl">
            <div class="bg-white relative shadow-sm border border-gray-100 sm:rounded-xl overflow-hidden">

                {{-- Flash messages --}}
                @if (session('success'))
                    <div class="m-4 p-4 bg-green-50 rounded-lg border border-green-200">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="m-4 p-4 bg-red-100 rounded-lg border border-red-200">
                        <p class="text-sm text-red-600">{{ session('error') }}</p>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-[11px] font-bold tracking-widest text-solare-arcilla uppercase bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Cliente</th>
                                <th class="px-6 py-4">Dirección de envío</th>
                                <th class="px-6 py-4">Fecha de pedido</th>
                                <th class="px-6 py-4">Estado de pago</th>
                                <th class="px-6 py-4">Estado de envío</th>
                                <th class="px-6 py-4">Notas</th>
                                <th class="px-6 py-4 text-center">Creado</th>
                                <th class="px-6 py-4 text-center">Actualizado</th>
                                <th class="px-6 py-4"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($pedidos as $pedido)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-xs font-medium text-gray-400">#{{ $pedido['id'] }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $pedido['nombre_cliente'] }}</td>
                                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $pedido['direccion_envio'] }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $pedido['fecha_pedido'] ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase rounded">
                                            {{ $pedido['estado_pago'] ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 bg-solare-arcilla/10 text-solare-arcilla text-[10px] font-bold uppercase rounded">
                                            {{ $pedido['estado_envio'] ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 text-xs max-w-xs truncate">
                                        {{ $pedido['notas'] ?? '—' }}</td>
                                    <td class="px-6 py-4 text-center text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($pedido['creado_en'])->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-center text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($pedido['actualizado_en'])->format('d/m/Y') }}</td>

                                    {{-- Menú acciones --}}
                                    <td class="px-6 py-4 text-right relative">
                                        <button id="btn-{{ $pedido['id'] }}"
                                            onclick="toggleMenu('menu-{{ $pedido['id'] }}')"
                                            class="inline-flex items-center p-1.5 text-gray-400 hover:text-solare-musgo hover:bg-gray-100 rounded-lg transition-all focus:outline-none"
                                            type="button">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>

                                        <div id="menu-{{ $pedido['id'] }}"
                                            class="hidden absolute right-12 mt-0 w-56 bg-white rounded-xl shadow-xl z-50 border border-gray-100 py-1 overflow-hidden">
                                            <p
                                                class="px-4 py-2 text-[10px] font-bold uppercase text-gray-400 tracking-widest border-b border-gray-50">
                                                Cambiar estado de envío
                                            </p>
                                            @foreach (['procesando pedido', 'pedido enviado', 'pedido entregado'] as $estado)
                                                <form action="{{ route('pedidos.estadoEnvio', $pedido['id']) }}"
                                                    method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="estado_envio" value="{{ $estado }}">
                                                    <button type="submit"
                                                        class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors
                                                    {{ $pedido['estado_envio'] === $estado ? 'font-bold text-solare-musgo' : 'text-gray-700' }}">
                                                        {{ ucfirst($estado) }}
                                                        @if ($pedido['estado_envio'] === $estado)
                                                            <span class="ml-1 text-xs">✓</span>
                                                        @endif
                                                    </button>
                                                </form>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-12 text-center text-gray-400 text-sm">
                                        No hay pedidos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleMenu(id) {
            const menu = document.getElementById(id);
            document.querySelectorAll('[id^="menu-"]').forEach(m => {
                if (m.id !== id) m.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
        }

        window.addEventListener('click', function(e) {
            if (!e.target.closest('button[id^="btn-"]') && !e.target.closest('[id^="menu-"]')) {
                document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>
@endsection
