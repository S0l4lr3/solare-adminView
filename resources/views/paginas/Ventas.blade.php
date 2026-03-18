@extends('layouts.app')

@section('title', 'Ventas')
@section('label', 'Comercial')
@section('header_title', 'Ventas')

@section('actions')
    <button class="bg-[#958174] text-white px-5 py-2 text-[11px] font-bold uppercase tracking-widest hover:bg-[#50594e] transition">+ Nuevo pedido</button>
@endsection

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-4 border border-gray-100 shadow-sm">
        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Ingresos Feb</p>
        <p class="serif text-2xl mt-1">$248,500</p>
        <p class="text-[10px] text-[#798273] mt-1">↑ 12% vs ene</p>
    </div>
    </div>

<div class="border-b border-gray-200 mb-6">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
        <li class="me-2">
            <a href="#" class="inline-block p-4 text-[#958174] border-b-2 border-[#958174] active font-bold uppercase tracking-widest text-[11px]">Pedidos</a>
        </li>
        <li class="me-2">
            <a href="#" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 transition uppercase tracking-widest text-[11px]">Clientes</a>
        </li>
        <li class="me-2">
            <a href="#" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 transition uppercase tracking-widest text-[11px]">Cotizaciones</a>
        </li>
    </ul>
</div>

<div class="bg-white border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="text-[10px] text-gray-400 uppercase bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-4">ID</th>
                <th class="px-6 py-4">Cliente</th>
                <th class="px-6 py-4">Fecha</th>
                <th class="px-6 py-4">Total</th>
                <th class="px-6 py-4">Estado</th>
                <th class="px-6 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-[#958174]">#SL-0891</td>
                <td class="px-6 py-4 font-medium text-gray-900">Hotel Marquesa</td>
                <td class="px-6 py-4 text-xs">14 Feb</td>
                <td class="px-6 py-4 font-bold">$18,200</td>
                <td class="px-6 py-4">
                    <span class="bg-green-100 text-green-800 text-[10px] font-bold px-3 py-1 rounded-sm">ENTREGADO</span>
                </td>
                <td class="px-6 py-4">
                    <button class="text-[#798273] font-bold text-[10px] uppercase hover:underline">Detalles</button>
                </td>
            </tr>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-[#958174]">#SL-0890</td>
                <td class="px-6 py-4 font-medium text-gray-900">Villa Palermo</td>
                <td class="px-6 py-4 text-xs">13 Feb</td>
                <td class="px-6 py-4 font-bold">$9,600</td>
                <td class="px-6 py-4">
                    <span class="bg-orange-100 text-orange-800 text-[10px] font-bold px-3 py-1 rounded-sm uppercase">En proceso</span>
                </td>
                <td class="px-6 py-4">
                    <button class="text-[#798273] font-bold text-[10px] uppercase hover:underline">Detalles</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection