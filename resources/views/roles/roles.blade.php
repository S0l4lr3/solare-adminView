@extends('layouts.app')

@section('title', 'Roles')
@section('label', 'Gestión de Seguridad')
@section('header_title', 'Roles y Jerarquías')

@section('actions')
    <a href="{{ route('roles.create') }}" class="bg-solare-musgo text-white px-4 py-2 text-[11px] font-bold uppercase hover:bg-opacity-90 shadow-md transition">+ Registrar Rol</a>
@endsection

@section('content')

    {{-- Mensajes de estado --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
            <p class="text-xs text-green-700 font-bold uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white border border-gray-100 shadow-sm overflow-hidden rounded-xl">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-[10px] text-gray-400 uppercase bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4" width="10%">ID</th>
                    <th class="px-6 py-4" width="30%">Nombre del Rol</th>
                    <th class="px-6 py-4" width="45%">Descripción / Permisos Sugeridos</th>
                    <th class="px-6 py-4" width="15%"><span class="sr-only">Acciones</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($roles as $rol)
                    @php $rol = (array)$rol; @endphp
                    <tr class="hover:bg-[#faf9f7] transition">
                        <td class="px-6 py-4 font-mono text-[11px] text-[#958174] font-bold">
                            #{{ str_pad($rol['id'], 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-900 uppercase tracking-wider">{{ $rol['nombre'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 italic">
                            {{ $rol['descripcion'] ?? 'Sin descripción detallada' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(!in_array(strtolower($rol['nombre']), ['administrador', 'ceo', 'vendedor']))
                                <form action="{{ route('roles.destroy', $rol['id']) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este rol jerárquico? Los usuarios asociados podrían perder acceso.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 font-bold uppercase text-[9px] tracking-widest transition">
                                        Eliminar
                                    </button>
                                </form>
                            @else
                                <span class="text-[8px] font-bold text-gray-300 uppercase tracking-tighter cursor-not-allowed">Protegido por Sistema</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-[10px] uppercase tracking-[3px] font-bold">No hay roles personalizados registrados.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 p-4 bg-solare-musgo/5 border border-solare-musgo/10 rounded-lg">
        <p class="text-[10px] text-solare-musgo font-bold uppercase tracking-widest mb-1">ℹ️ Nota de Seguridad</p>
        <p class="text-xs text-gray-600">Los roles base (Administrador, CEO, Vendedor) están protegidos para garantizar la integridad de la red Solare Muebles.</p>
    </div>

@endsection
