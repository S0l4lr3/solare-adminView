@extends('layouts.app')

@section('title', 'Pedidos')
@section('label', 'Panel General')
@section('header_title', 'Bienvenido, Admin')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center">
                <h4 class="serif text-lg">Pedidos recientes</h4>
                <a href="/Ventas"
                    class="text-[10px] font-bold text-[#798273] uppercase border border-[#798273] px-3 py-1 hover:bg-[#798273] hover:text-white transition">Editar
                    pedidos</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-[10px] text-gray-400 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Cliente</th>
                            <th class="px-6 py-3">Direccion de envio </th>
                            <th class="px-6 py-3">Fecha de pedido</th>
                            <th class="px-6 py-3">Estado de pago</th>
                            <th class="px-6 py-3">Estado de envio</th>
                            <th class="px-6 py-3">Notas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {{-- @foreach ($pedidos as $pedido)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-[#958174]">#{{ $pedido->id }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $pedido->cliente_id }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $pedido->direccion_envio_id }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $pedido->fecha_pedido }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $pedido->estado_pago }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $pedido->estado_envio }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $pedido->notas }}</td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border border-gray-100 shadow-sm p-5">
            <h4 class="serif text-lg mb-4">Más vendidos</h4>
            <div class="space-y-4">
                <div class="flex items-center gap-4 bg-gray-50 p-3">
                    <span class="text-2xl">🛋️</span>
                    <div class="flex-1">
                        <p class="text-xs font-bold"></p>
                        <p class="text-[10px] text-gray-400"></p>
                    </div>
                    <span class="bg-[#958174] text-white text-[8px] px-2 py-0.5 rounded uppercase font-bold"></span>
                </div>
            </div>
        </div>

    </div>
@endsection
