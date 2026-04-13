@extends('layouts.app')

@section('label', 'Detalle del Pedido #' . $pedido['id'])
@section('header_title', 'Información de Empaquetado y Envío')

@section('actions')
    <a href="{{ route('pedidos.index') }}"
        class="flex items-center justify-center text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 transition-all shadow-sm">
        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Volver a la lista
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in">
        
        <!-- Columna Izquierda: Productos a Empaquetar -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-solare-arcilla">Productos en este pedido</h3>
                    <span class="px-3 py-1 bg-solare-musgo/10 text-solare-musgo text-[10px] font-bold uppercase rounded-full">
                        {{ count($pedido['detalles']) }} Items
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 text-[10px] font-bold uppercase text-gray-400 tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Producto / Variante</th>
                                <th class="px-6 py-4 text-center">Cantidad</th>
                                <th class="px-6 py-4 text-right">Precio Unit.</th>
                                <th class="px-6 py-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($pedido['detalles'] as $detalle)
                                <tr class="hover:bg-gray-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            @if($detalle['variante']['producto']['imagen_url'])
                                                <img src="{{ rtrim(env('IMAGE_URL'), '/') . '/' . $detalle['variante']['producto']['imagen_url'] }}" 
                                                     class="w-12 h-12 rounded-lg object-cover border border-gray-100 shadow-sm" />
                                            @else
                                                <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center border border-dashed border-gray-200 text-[8px] text-gray-400">
                                                    NO IMG
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-bold text-gray-800 text-sm">
                                                    {{ $detalle['variante']['producto']['nombre'] ?? 'Desconocido' }}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-0.5">
                                                    {{ $detalle['variante']['material']['nombre'] ?? 'Standard' }} | 
                                                    {{ $detalle['variante']['sku'] ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-solare-musgo">
                                        {{ $detalle['cantidad'] }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-600 text-sm">
                                        ${{ number_format($detalle['precio_unitario'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900 text-sm">
                                        ${{ number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50/50">
                            @php
                                $subtotalProductos = 0;
                                foreach($pedido['detalles'] as $detalle) {
                                    $subtotalProductos += $detalle['cantidad'] * $detalle['precio_unitario'];
                                }
                                $esEnvioADomicilio = !empty($pedido['direccion_envio_id']);
                                $costoEnvio = $esEnvioADomicilio ? 200 : 0;
                                $iva = $subtotalProductos * 0.16;
                                $totalCalculado = $subtotalProductos + $iva + $costoEnvio;
                            @endphp
                            <tr class="border-t border-gray-100">
                                <td colspan="3" class="px-6 py-2 text-right text-[10px] font-bold uppercase text-gray-400">Subtotal Productos</td>
                                <td class="px-6 py-2 text-right font-medium text-gray-700 text-sm">
                                    ${{ number_format($subtotalProductos, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-[10px] font-bold uppercase text-gray-400">IVA (16%)</td>
                                <td class="px-6 py-2 text-right font-medium text-gray-700 text-sm">
                                    ${{ number_format($iva, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-[10px] font-bold uppercase text-gray-400">
                                    {{ $esEnvioADomicilio ? 'Envío a Domicilio' : 'Recoger en Tienda' }}
                                </td>
                                <td class="px-6 py-2 text-right font-medium text-gray-700 text-sm">
                                    ${{ number_format($costoEnvio, 2) }}
                                </td>
                            </tr>
                            <tr class="bg-solare-musgo/5">
                                <td colspan="3" class="px-6 py-4 text-right text-xs font-black uppercase text-solare-musgo">Total a Pagar</td>
                                <td class="px-6 py-4 text-right font-black text-xl text-solare-musgo">
                                    ${{ number_format($totalCalculado, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Notas del Pedido -->
            @if($pedido['notas'])
                <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-6">
                    <h4 class="text-[10px] font-bold uppercase text-amber-700 tracking-widest mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Instrucciones / Notas del Cliente
                    </h4>
                    <p class="text-sm text-amber-900 leading-relaxed italic">"{{ $pedido['notas'] }}"</p>
                </div>
            @endif
        </div>

        <!-- Columna Derecha: Datos de Envío y Estados -->
        <div class="space-y-6">
            
            <!-- Cliente y Envío -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-solare-arcilla mb-4 border-b border-gray-50 pb-2">Destinatario</h3>
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-solare-arcilla/10 rounded-full flex items-center justify-center text-solare-arcilla font-bold text-lg">
                        {{ strtoupper(substr($pedido['cliente']['usuario']['nombre'] ?? 'C', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $pedido['cliente']['usuario']['nombre'] ?? 'Sin nombre' }}</p>
                        <p class="text-xs text-gray-500">{{ $pedido['cliente']['usuario']['correo'] ?? 'Sin correo' }}</p>
                        <p class="text-xs text-gray-500">{{ $pedido['cliente']['telefono'] ?? 'Sin teléfono' }}</p>
                    </div>
                </div>

                <h3 class="text-xs font-bold uppercase tracking-widest text-solare-arcilla mb-2">Método de Entrega</h3>
                @if($pedido['direccion_envio'])
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <p class="text-[10px] font-bold uppercase text-solare-musgo mb-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                            Envío a Domicilio
                        </p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $pedido['direccion_envio']['calle'] }} #{{ $pedido['direccion_envio']['numero_exterior'] }}
                            @if(!empty($pedido['direccion_envio']['numero_interior']))
                                (Int. {{ $pedido['direccion_envio']['numero_interior'] }})
                            @endif
                        </p>
                        <p class="text-xs text-gray-600">{{ $pedido['direccion_envio']['colonia'] }}, {{ $pedido['direccion_envio']['codigo_postal'] }}</p>
                        <p class="text-xs text-gray-600">{{ $pedido['direccion_envio']['ciudad'] }}, {{ $pedido['direccion_envio']['estado'] }}</p>
                        @if(!empty($pedido['direccion_envio']['referencias']))
                            <p class="text-[10px] text-solare-musgo mt-2 italic font-medium">Ref: {{ $pedido['direccion_envio']['referencias'] }}</p>
                        @endif
                    </div>
                @else
                    <div class="bg-solare-musgo/10 p-5 rounded-lg border border-solare-musgo/20 text-center">
                        <div class="flex justify-center mb-2">
                            <svg class="w-8 h-8 text-solare-musgo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <p class="text-xs text-solare-musgo font-black uppercase tracking-widest">Recoger en Tienda</p>
                        <p class="text-[10px] text-gray-500 mt-1">El cliente pasará por su pedido a la sucursal física.</p>
                    </div>
                @endif
            </div>

            <!-- Gestión de Estados -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-solare-arcilla mb-4 border-b border-gray-50 pb-2">Gestión de Orden</h3>
                
                <form action="{{ route('pedidos.update', $pedido['id']) }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-widest mb-1.5">Estado del Pago</label>
                        <select name="estado_pago" class="w-full bg-gray-50 border border-gray-200 text-sm rounded-lg focus:ring-solare-musgo focus:border-solare-musgo p-2.5 outline-none">
                            <option value="pendiente" {{ $pedido['estado_pago'] == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="pagado" {{ $pedido['estado_pago'] == 'pagado' ? 'selected' : '' }}>Pagado</option>
                            <option value="reembolsado" {{ $pedido['estado_pago'] == 'reembolsado' ? 'selected' : '' }}>Reembolsado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-widest mb-1.5">Estado del Envío</label>
                        <select name="estado_envio" class="w-full bg-gray-50 border border-gray-200 text-sm rounded-lg focus:ring-solare-musgo focus:border-solare-musgo p-2.5 outline-none">
                            <option value="procesando" {{ $pedido['estado_envio'] == 'procesando' ? 'selected' : '' }}>Procesando (Empaquetado)</option>
                            <option value="en_camino" {{ $pedido['estado_envio'] == 'en_camino' ? 'selected' : '' }}>En Camino / Despachado</option>
                            <option value="entregado" {{ $pedido['estado_envio'] == 'entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelado" {{ $pedido['estado_envio'] == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-solare-musgo text-white font-bold text-xs uppercase tracking-widest py-3 rounded-lg hover:bg-opacity-90 transition-all shadow-sm">
                        Actualizar Pedido
                    </button>
                </form>
            </div>

            <!-- Estampa de Tiempo -->
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">
                    Orden generada el {{ \Carbon\Carbon::parse($pedido['fecha_pedido'])->format('d/m/Y H:i') }}
                </p>
            </div>

        </div>
    </div>
@endsection
