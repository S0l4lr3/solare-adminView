@extends('layouts.app')

@section('title', 'Inventario')
@section('label', 'Almacén')
@section('header_title', 'Inventario')

@section('actions')
    <a href="{{ route('inventario.exportar', array_merge(['formato' => 'pdf'], request()->all())) }}" 
       class="border border-[#958174] text-[#958174] px-4 py-2 text-[10px] font-bold uppercase hover:bg-[#958174] hover:text-white transition flex items-center gap-2">
       <span>📄</span> PDF
    </a>
    <a href="{{ route('inventario.exportar', array_merge(['formato' => 'excel'], request()->all())) }}" 
       class="border border-[#958174] text-[#958174] px-4 py-2 text-[10px] font-bold uppercase hover:bg-[#958174] hover:text-white transition flex items-center gap-2">
       <span>📊</span> EXCEL
    </a>
    <button class="bg-[#958174] text-white px-4 py-2 text-[11px] font-bold uppercase hover:bg-[#50594e] transition">+ Agregar</button>
@endsection

@section('content')

{{-- BARRA DE CONTROL MAESTRO --}}
<form action="{{ route('inventario') }}" method="GET" class="bg-white p-4 border border-gray-100 shadow-sm mb-6 space-y-4 md:space-y-0 md:flex md:items-end md:gap-4">
    
    <div class="flex-1">
        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Búsqueda rápida</label>
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 text-sm focus:ring-[#958174]" 
                placeholder="Nombre o SKU...">
            <span class="absolute left-3 top-2.5 text-gray-400 italic">⌕</span>
        </div>
    </div>

    <div class="w-full md:w-48">
        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Categoría</label>
        <select name="categoria_id" class="w-full bg-gray-50 border border-gray-200 p-2 text-sm focus:ring-[#958174]">
            <option value="">Todas</option>
            @foreach($categorias as $cat)
                @php $cat = (array)$cat; @endphp
                <option value="{{ $cat['id'] }}" {{ request('categoria_id') == $cat['id'] ? 'selected' : '' }}>
                    {{ $cat['nombre'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="w-full md:w-48">
        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Material</label>
        <select name="material_id" class="w-full bg-gray-50 border border-gray-200 p-2 text-sm focus:ring-[#958174]">
            <option value="">Todos</option>
            @foreach($materiales as $mat)
                @php $mat = (array)$mat; @endphp
                <option value="{{ $mat['id'] }}" {{ request('material_id') == $mat['id'] ? 'selected' : '' }}>
                    {{ $mat['nombre'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex items-center gap-2 pb-2">
        <input type="checkbox" name="stock_bajo" id="stock_bajo" value="1" {{ request('stock_bajo') ? 'checked' : '' }}
            class="w-4 h-4 text-solare-musgo border-gray-300 rounded focus:ring-solare-musgo">
        <label for="stock_bajo" class="text-xs font-bold text-orange-600 uppercase">Stock Bajo</label>
    </div>

    <div class="flex gap-2">
        <button type="submit" class="bg-solare-musgo text-white px-6 py-2 text-[11px] font-bold uppercase hover:bg-opacity-90 shadow-md transition">Filtrar</button>
        <a href="{{ route('inventario') }}" class="bg-gray-100 text-gray-400 px-4 py-2 text-[11px] font-bold uppercase hover:bg-gray-200 transition">Limpiar</a>
    </div>
</form>

{{-- Mensajes de estado --}}
@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
        <p class="text-xs text-green-700 font-bold uppercase tracking-widest">{{ session('success') }}</p>
    </div>
@endif
@if(session('error') || isset($error))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
        <p class="text-xs text-red-700 font-bold uppercase tracking-widest">{{ session('error') ?? $error }}</p>
    </div>
@endif

<div class="bg-white border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="text-[10px] text-gray-400 uppercase bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-4">ID / SKU</th>
                <th class="px-6 py-4">
                    <a href="{{ route('inventario', array_merge(request()->all(), ['sort' => 'producto', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-[#958174]">
                        Producto ↕
                    </a>
                </th>
                <th class="px-6 py-4">Material / Color</th>
                <th class="px-6 py-4">
                    <a href="{{ route('inventario', array_merge(request()->all(), ['sort' => 'stock', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-[#958174]">
                        Stock Actual ↕
                    </a>
                </th>
                <th class="px-6 py-4">
                    <a href="{{ route('inventario', array_merge(request()->all(), ['sort' => 'precio', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-[#958174]">
                        Precio Venta ↕
                    </a>
                </th>
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
                        <button onclick="openModal('{{ $item['variante_id'] }}', '{{ $item['producto'] }}', '{{ $item['stock_total'] }}')" 
                                class="text-[#958174] hover:underline font-medium uppercase text-[9px] tracking-widest font-bold">
                            Gestionar
                        </button>
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

{{-- MODAL DE GESTIÓN DE STOCK (Anti-Robo Hormiga) --}}
<div id="modal-stock" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-gray-100">
        <div class="bg-solare-musgo p-6 text-white">
            <h3 class="serif text-xl mb-1">Ajuste de Almacén</h3>
            <p id="modal-product-name" class="text-[10px] uppercase tracking-widest text-white/60 font-bold">Cargando...</p>
        </div>
        
        <form id="form-stock" method="POST" action="" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Tipo de Movimiento</label>
                <select name="tipo" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm focus:ring-[#958174] outline-none">
                    <option value="entrada">ENTRADA (Compra / Reposición)</option>
                    <option value="salida">SALIDA (Merma / Robo / Daño)</option>
                    <option value="ajuste">AJUSTE (Conteo Físico Directo)</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Cantidad</label>
                    <input type="number" name="cantidad" required min="1" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm outline-none" placeholder="0">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Stock Actual</label>
                    <input type="text" id="modal-current-stock" disabled class="w-full bg-gray-100 border border-gray-200 rounded-lg p-3 text-sm text-gray-400 font-bold">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Motivo del Movimiento</label>
                <textarea name="motivo" required rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm outline-none" placeholder="Ej: Mercancía dañada en transporte o compra a proveedor X..."></textarea>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-50">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 text-[10px] font-bold uppercase text-gray-400 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancelar</button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-[10px] font-bold uppercase text-white bg-solare-musgo hover:bg-opacity-90 rounded-lg shadow-md transition">Actualizar Stock</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id, name, stock) {
        document.getElementById('modal-product-name').innerText = name;
        document.getElementById('modal-current-stock').value = stock;
        document.getElementById('form-stock').action = `/Inventario/${id}`;
        document.getElementById('modal-stock').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal-stock').classList.add('hidden');
    }

    // Cerrar al hacer clic fuera del modal
    window.onclick = function(event) {
        const modal = document.getElementById('modal-stock');
        if (event.target == modal) closeModal();
    }
</script>
@endsection
