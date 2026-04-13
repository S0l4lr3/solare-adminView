@extends('layouts.app')

@section('title', 'Dashboard')
@section('label', 'Panel General')
@section('header_title', 'Bienvenido, ' . (session('user')['nombre'] ?? 'Administrador'))

@section('content')
    {{-- Alerta de Error de Conexión (Solo si falla la API) --}}
    @if(isset($error) && $error)
        <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-6">
            <p class="text-[10px] text-red-700 font-bold uppercase tracking-widest">Error de Sincronización: {{ $error }}</p>
        </div>
    @endif

    {{-- ALERTAS CRÍTICAS (Monitor de Almacén) --}}
    @if(($dashboard['ajustes_manuales_24h'] ?? 0) > 0 || count($dashboard['stock_critico'] ?? []) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        @if(($dashboard['ajustes_manuales_24h'] ?? 0) > 0)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 shadow-sm animate-pulse">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-red-500 text-xl">⚠️</span>
                </div>
                <div class="ml-3">
                    <p class="text-[10px] font-bold text-red-700 uppercase tracking-widest">Auditoría de Almacén Requerida</p>
                    <p class="text-sm text-red-600 font-medium">Se detectaron {{ $dashboard['ajustes_manuales_24h'] }} ajustes manuales de stock en las últimas 24 horas.</p>
                </div>
            </div>
        </div>
        @endif

        @if(count($dashboard['stock_critico'] ?? []) > 0)
        <div class="bg-orange-50 border-l-4 border-orange-500 p-4 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-orange-500 text-xl">📉</span>
                </div>
                <div class="ml-3">
                    <p class="text-[10px] font-bold text-orange-700 uppercase tracking-widest">Alerta de Ventas Perdidas</p>
                    <p class="text-sm text-orange-600 font-medium">{{ count($dashboard['stock_critico']) }} productos tienen stock crítico (menos de 3 unidades).</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $stats = [
                [
                    'l' => 'Ventas este mes',
                    'v' => $dashboard['ventas_mes']['cantidad'] ?? '0',
                    'd' => $dashboard['ventas_mes']['total'] ?? '$0',
                    'c' => '#958174',
                ],
                [
                    'l' => 'Stock Real',
                    'v' => $dashboard['piezas_stock'] ?? '0',
                    'd' => 'Activos: ' . ($dashboard['valor_inventario'] ?? '$0'),
                    'c' => '#798273',
                ],
                [
                    'l' => 'Stock Crítico',
                    'v' => count($dashboard['stock_critico'] ?? []),
                    'd' => 'piezas por agotar',
                    'c' => '#c2410c',
                ],
                [
                    'l' => 'Pedidos activos',
                    'v' => $dashboard['pedidos_activos'] ?? '0',
                    'd' => 'sin entregar',
                    'c' => '#958174',
                ],
            ];
        @endphp

        @foreach ($stats as $s)
            <div class="bg-white p-6 border border-gray-100 shadow-sm" style="border-top: 3px solid {{ $s['c'] }}">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $s['l'] }}</p>
                <h3 class="serif text-3xl font-normal text-gray-900">{{ $s['v'] }}</h3>
                <p class="text-[11px] text-green-600 font-medium mt-2">{{ $s['d'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center">
                <h4 class="serif text-lg">Pedidos recientes</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-[10px] text-gray-400 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Cliente</th>
                            <th class="px-6 py-3">Producto</th>
                            <th class="px-6 py-3 text-center">Cant.</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($dashboard['pedidos_recientes'] as $detalle)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-[#958174]">#{{ $detalle['pedido_id'] }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $detalle['cliente'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $detalle['producto'] }}</td>

                                <td class="px-6 py-4 font-font-semibold text-center">
                                    {{ $detalle['cantidad'] }}
                                </td>

                                <td class="px-6 py-4 font-semibold">{{ $detalle['total'] }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase">
                                        {{ $detalle['estado_envio'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-400 text-xs">
                                    Sin pedidos recientes
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border border-gray-100 shadow-sm p-5">
            <h4 class="serif text-lg mb-4">Más vendidos</h4>
            <div class="space-y-4">

                @forelse($dashboard['mas_vendidos'] as $index => $producto)
                    <div class="flex items-center gap-4 bg-gray-50 p-3">
                        <span class="text-2xl">{{ $index === 0 ? '🏆' : '🔥' }}</span>

                        <div class="flex-1">
                            <p class="text-xs font-bold">{{ $producto['nombre'] }}</p>
                            <p class="text-[10px] text-gray-400">
                                Vendidos: {{ $producto['total_vendido'] }} piezas
                            </p>
                        </div>

                        <div class="text-right flex flex-col items-end">
                            <span class="bg-[#958174] text-white text-[8px] px-2 py-0.5 rounded uppercase font-bold mb-1">
                                TOP {{ $index + 1 }}
                            </span>
                            <span class="text-[10px] font-bold text-gray-600">
                                ${{ number_format($producto['precio_referencia'], 2) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-center text-gray-400 text-xs">
                        <p>Aún no hay suficientes datos de ventas.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
@endsection
