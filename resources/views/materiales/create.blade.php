@extends('layouts.app')

@section('title', 'Añadir Material')
@section('label', 'Materiales de Exterior')
@section('header_title', 'Registrar Nuevo Insumo')

@section('content')
<section class="bg-[#f7f5f2]">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16 bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
        <h2 class="serif mb-6 text-3xl font-normal text-gray-900 border-b pb-4">Detalles del Material</h2>

        <form action="{{ route('materiales.store') }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 rounded-lg">
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Nombre del Material</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                        class="bg-gray-50 border border-solare-musgo text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        placeholder="Ej. Madera de Teca, Aluminio Anodizado" required>
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Descripción (Opcional)</label>
                    <textarea name="descripcion" rows="6"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-solare-musgo focus:ring-2 focus:ring-solare-musgo focus:border-solare-musgo outline-none"
                        placeholder="Describe las propiedades o el uso de este material...">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-medium text-white bg-solare-musgo rounded-lg hover:bg-opacity-90 transition-all shadow-md">
                    Guardar Material
                </button>
                <a href="{{ route('materiales.index') }}"
                    class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
