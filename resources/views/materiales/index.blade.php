@extends('layouts.app')

@section('title', 'Gestión de Materiales')
@section('label', 'Administración de Insumos')
@section('header_title', 'Catálogo de Materiales')

@section('actions')
    <a href="{{ route('materiales.create') }}" 
        class="flex items-center justify-center text-white bg-solare-musgo hover:bg-opacity-90 focus:ring-4 focus:ring-solare-musgo/30 font-medium rounded-lg text-sm px-5 py-2.5 transition-all shadow-sm">
        <svg class="h-4 w-4 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
        </svg>
        Añadir Material
    </a>
@endsection

@section('content')
<section class="antialiased">
    <div class="mx-auto max-w-screen-xl">
        <div class="bg-white relative shadow-sm border border-gray-100 sm:rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-[11px] font-bold tracking-widest text-solare-arcilla uppercase bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4">ID</th>
                            <th scope="col" class="px-6 py-4">Nombre del Material</th>
                            <th scope="col" class="px-6 py-4">Descripción</th>
                            <th scope="col" class="px-6 py-4">Creado el</th>
                            <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($materiales as $material)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                {{-- @dd($material) --}}
                                <td class="px-6 py-4 text-xs font-medium text-gray-400">#{{ $material['id'] }}</td>
                                <th scope="row" class="px-6 py-4 font-semibold text-gray-800 whitespace-nowrap">
                                    {{ $material['nombre'] }}
                                </th>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-gray-500 line-clamp-1">
                                        {{ $material['descripcion'] ?? 'Sin descripción' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($material['creado_en'])->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('materiales.edit',$material['id']) }}" 
                                            class="text-solare-musgo hover:text-opacity-80 font-medium">Editar</a>
                                        <form action="{{ route('materiales.destroy',$material['id']) }}" method="POST" onsubmit="return confirm('¿Eliminar este material?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            {{-- @dd($material) --}}
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                                    No hay materiales registrados actualmente.
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
