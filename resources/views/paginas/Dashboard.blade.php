@extends('layouts.app')

@section('title', 'Dashboard')
@section('label', 'Panel General')
@section('header_title', 'Bienvenido, Admin')

@section('actions')
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $stats = [
                ['l' => 'Ventas este mes', 'v' => '$0', 'd' => '+12%', 'c' => '#958174'],
                ['l' => 'Piezas en stock', 'v' => '0', 'd' => '+5%', 'c' => '#798273'],
                ['l' => 'Pedidos activos', 'v' => '0', 'd' => '-3', 'c' => '#958174'],
                ['l' => 'Clientes nuevos', 'v' => '0', 'd' => '+8%', 'c' => '#798273'],
            ];
        @endphp

        @foreach ($stats as $s)
            <div class="bg-white p-6 border border-gray-100 shadow-sm" style="border-top: 3px solid {{ $s['c'] }}">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $s['l'] }}</p>
                <h3 class="serif text-3xl font-normal text-gray-900">{{ $s['v'] }}</h3>
                <p class="text-[11px] text-green-600 font-medium mt-2">{{ $s['d'] }} vs mes anterior</p>
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
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-[#958174]">#SL-0891</td>
                            <td class="px-6 py-4 text-gray-900">Hotel Marquesa</td>
                            <td class="px-6 py-4 font-semibold">$18,200</td>
                            <td class="px-6 py-4">
                                <span
                                    class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded">ENTREGADO</span>
                            </td>
                        </tr>
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
                        <p class="text-xs font-bold">Sofá Málaga</p>
                        <p class="text-[10px] text-gray-400">$4,200</p>
                    </div>
                    <span class="bg-[#958174] text-white text-[8px] px-2 py-0.5 rounded uppercase font-bold">TOP</span>
                </div>
            </div>
        </div>
    </div>
@endsection
