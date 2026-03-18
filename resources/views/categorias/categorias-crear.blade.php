@extends('layouts.app')

@section('title', 'Nueva Categoría')
@section('label', 'Gestión de Catálogo')
@section('header_title', 'Crear Nueva Categoría')

@section('content')

<section class="bg-[#f7f5f2]">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="serif mb-6 text-3xl font-normal text-gray-900">Detalles de Categoría</h2>

        <form action="{{ route('categorias.store') }}" method="POST">
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
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Nombre de la categoría
                    </label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                        class="bg-gray-50 border border-gray-400 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-solare-musgo focus:border-solare-musgo dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Ej. Salas" required>
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Descripción
                    </label>
                    <textarea name="descripcion" rows="5"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Descripción de la categoría">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-medium text-white bg-solare-musgo rounded-lg hover:bg-opacity-90 transition-all shadow-sm">
                    Guardar Categoría
                </button>
                <a href="{{ route('categorias.index') }}"
                    class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</section>

@endsection