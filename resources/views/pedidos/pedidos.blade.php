@extends('layouts.app')

@section('label', 'Gestión de Pedidos')
@section('header_title', 'Órdenes de Venta')

@section('content')
    <section class="antialiased">
        <div class="mx-auto max-w-screen-xl">
            
            <!-- Barra de Filtros Mejorada -->
            <div class="mb-6 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <form action="{{ route('pedidos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                    
                    <!-- Búsqueda por Nombre -->
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-widest mb-1.5">Cliente</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nombre..."
                               class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg focus:ring-solare-musgo focus:border-solare-musgo p-2.5 outline-none transition-all">
                    </div>

                    <!-- Fecha Inicio -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-widest mb-1.5">Desde</label>
                        <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" 
                               class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg focus:ring-solare-musgo focus:border-solare-musgo p-2.5 outline-none transition-all">
                    </div>

                    <!-- Fecha Fin -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-widest mb-1.5">Hasta</label>
                        <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" 
                               class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg focus:ring-solare-musgo focus:border-solare-musgo p-2.5 outline-none transition-all">
                    </div>

                    <!-- Estado Pago -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-widest mb-1.5">Pago</label>
                        <select name="estado_pago" class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg focus:ring-solare-musgo focus:border-solare-musgo p-2.5 outline-none">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ request('estado_pago') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="pagado" {{ request('estado_pago') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                        </select>
                    </div>

                    <!-- Estado Envío (INCLUYE CANCELADOS) -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-widest mb-1.5">Envío / Status</label>
                        <select name="estado_envio" class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg focus:ring-solare-musgo focus:border-solare-musgo p-2.5 outline-none">
                            <option value="">Todos</option>
                            <option value="procesando" {{ request('estado_envio') == 'procesando' ? 'selected' : '' }}>Procesando</option>
                            <option value="en_camino" {{ request('estado_envio') == 'en_camino' ? 'selected' : '' }}>En Camino</option>
                            <option value="entregado" {{ request('estado_envio') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelado" {{ request('estado_envio') == 'cancelado' ? 'selected' : '' }}>Cancelado ❌</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-solare-musgo text-white font-bold text-[10px] uppercase tracking-widest py-3 rounded-lg hover:bg-opacity-90 transition-all shadow-sm">
                            Filtrar
                        </button>
                        <a href="{{ route('pedidos.index') }}" class="px-4 bg-gray-100 text-gray-400 font-bold text-[10px] uppercase tracking-widest py-3 rounded-lg hover:bg-gray-200 transition-all flex items-center justify-center border border-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        </a>
                    </div>

                </form>
            </div>

            <!-- Tabla de Pedidos -->
            <div class="bg-white relative shadow-sm border border-gray-100 sm:rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-[11px] font-bold tracking-widest text-solare-arcilla uppercase bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Cliente</th>
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4 text-right">Total</th>
                                <th class="px-6 py-4 text-center">Pago</th>
                                <th class="px-6 py-4 text-center">Envío</th>
                                <th class="px-6 py-4"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($pedidos as $pedido)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-xs font-medium text-gray-400">#{{ $pedido['id'] }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                        {{ $pedido['cliente']['usuario']['nombre'] ?? 'Cliente Desconocido' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-xs">
                                        {{ \Carbon\Carbon::parse($pedido['fecha_pedido'])->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900">
                                        ${{ number_format($pedido['total'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 {{ ($pedido['estado_pago'] ?? 'pendiente') == 'pagado' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }} text-[10px] font-bold uppercase rounded">
                                            {{ $pedido['estado_pago'] ?? 'pendiente' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $colorStatus = match($pedido['estado_envio'] ?? 'procesando') {
                                                'entregado' => 'bg-green-100 text-green-800',
                                                'cancelado' => 'bg-red-100 text-red-800',
                                                'en_camino' => 'bg-blue-100 text-blue-800',
                                                default => 'bg-solare-arcilla/10 text-solare-arcilla',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 {{ $colorStatus }} text-[10px] font-bold uppercase rounded shadow-sm">
                                            {{ str_replace('_', ' ', $pedido['estado_envio'] ?? 'procesando') }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('pedidos.show', $pedido['id']) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-solare-musgo text-white text-[10px] font-bold uppercase tracking-widest rounded hover:bg-opacity-90 transition-all shadow-sm">
                                            Ver Detalle / Empacar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-sm italic">
                                        No se encontraron pedidos con los filtros aplicados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
