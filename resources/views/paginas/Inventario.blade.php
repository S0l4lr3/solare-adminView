@extends('layouts.app')

@section('title', 'Inventario')
@section('label', 'Almacén')
@section('header_title', 'Inventario')

@section('actions')
    <button class="border border-[#958174] text-[#958174] px-4 py-2 text-[11px] font-bold uppercase hover:bg-[#958174] hover:text-white transition">Exportar</button>
    <button class="bg-[#958174] text-white px-4 py-2 text-[11px] font-bold uppercase hover:bg-[#50594e] transition">+ Agregar</button>
@endsection

@section('content')
<div class="flex flex-col md:flex-row gap-4 mb-6">
    <div class="relative flex-1">
        <input type="text" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 text-sm focus:ring-[#958174] focus:border-[#958174]" placeholder="Buscar por nombre o SKU...">
        <span class="absolute left-3 top-2.5 text-gray-400 italic">⌕</span>
    </div>
    <div class="flex gap-2 overflow-x-auto pb-2">
        @foreach(['Todos', 'Sofás', 'Mesas', 'Sillas', 'Tumbonas'] as $cat)
            <button class="whitespace-nowrap px-4 py-1.5 text-[10px] font-bold uppercase border border-gray-200 bg-white text-gray-400 hover:border-[#958174] hover:text-[#958174] transition">{{ $cat }}</button>
        @endforeach
    </div>
</div>

@if(isset($error))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
        <p class="text-xs text-red-700 font-bold uppercase tracking-widest">{{ $error }}</p>
    </div>
@endif

<div class="bg-white border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="text-[10px] text-gray-400 uppercase bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-4">ID / SKU</th>
                <th class="px-6 py-4">Producto</th>
                <th class="px-6 py-4">Categoría</th>
                <th class="px-6 py-4">Stock Actual</th>
                <th class="px-6 py-4">Precio Base</th>
                <th class="px-6 py-4">Estado Red</th>
                <th class="px-6 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-600">
            @forelse($stock as $item)
                {{-- Convertimos a array si viene como objeto stdClass --}}
                @php $item = (array)$item; @endphp
                <tr class="hover:bg-[#faf9f7] transition">
                    <td class="px-6 py-4 font-mono text-[11px] text-[#958174] font-bold">
                        SL-{{ str_pad($item['id'], 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item['producto']['nombre'] ?? 'Sin nombre' }}</td>
                    <td class="px-6 py-4 uppercase text-[10px] tracking-wider">{{ $item['material']['nombre'] ?? 'General' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-16 bg-gray-100 h-1 rounded-full">
                                <div class="h-1 rounded-full {{ $item['existencias'] > 5 ? 'bg-sage-green' : 'bg-orange-400' }}" 
                                     style="width: {{ min(($item['existencias'] / 20) * 100, 100) }}%"></div>
                            </div>
                            <span class="font-bold text-gray-900">{{ $item['existencias'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900">
                        {{-- El precio lo tomamos de una columna por defecto si no viene en esta vista --}}
                        ${{ number_format($item['producto']['precio_base'] ?? 0, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        @if($item['existencias'] > 5)
                            <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-1 rounded">DISPONIBLE</span>
                        @elseif($item['existencias'] > 0)
                            <span class="bg-orange-100 text-orange-800 text-[10px] font-bold px-2.5 py-1 rounded">CRÍTICO</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2.5 py-1 rounded">SIN STOCK</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <button class="text-[#958174] hover:underline font-medium uppercase text-[9px] tracking-widest font-bold">Gestionar</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <p class="text-[10px] uppercase tracking-[3px] text-gray-400 font-bold">
                            No hay muebles registrados en el inventario.
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
