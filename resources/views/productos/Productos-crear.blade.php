@extends('layouts.app')

@section('title', 'Nuevo Producto')
@section('label', 'Gestión de Catálogo')
@section('header_title', 'Registrar Nuevo Producto')

@section('content')

    <section class="bg-[#f7f5f2]">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="serif mb-6 text-3xl font-normal text-gray-900">Información del Producto</h2>

            <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del
                            producto</label>
                        <input type="text" name="nombre"
                            class="bg-gray-50 border border-gray-400 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-solare-musgo focus:border-solare-musgo dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Nombre del producto" required>
                    </div>

                    <div class="w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Precio unitario</label>
                        <input type="number" name="precio_base"
                            class="bg-gray-50 border border-gray-400 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-solare-musgo focus:border-solare-musgo dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="$2999" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoría</label>
                        <select name="categoria_id"
                            class="bg-gray-50 border border-gray-400 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-solare-musgo focus:border-solare-musgo dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Selecciona una categoría</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SKU</label>
                        <input type="text" name="sku_base"
                            class="bg-gray-50 border border-gray-400 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-solare-musgo focus:border-solare-musgo dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="SKU del producto">
                    </div>

                    <div class="sm:col-span-2 border border-gray-400 rounded-lg p-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Imagen principal del
                            producto</label>
                        <div id="dropzone"
                            class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:border-gray-600 transition-colors">
                            <img id="preview" src="" class="hidden h-48 object-contain mb-2 rounded" />
                            <div id="dropzone-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click para cargar</span> o
                                    arrastra aquí</p>
                                <p class="text-xs text-gray-500">PNG, JPG (máx. 2MB)</p>
                            </div>
                            <input id="dropzone-file" type="file" name="imagen" class="hidden"
                                accept="image/png, image/jpeg" />
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
                        <textarea name="descripcion" rows="8"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Tu descripción"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-solare-musgo rounded-lg hover:bg-opacity-90 transition-all shadow-sm">
                        Registrar Producto
                    </button>
                    <a href="{{ route('productos.index') }}"
                        class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </section>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('dropzone-file');
        const preview = document.getElementById('preview');
        const placeholder = document.getElementById('dropzone-placeholder');

        function mostrarPreview(file) {
            if (!file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        dropzone.addEventListener('click', (e) => {
            e.stopPropagation();
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files[0]) mostrarPreview(fileInput.files[0]);
        });

        dropzone.addEventListener('dragover', e => {
            e.preventDefault();
            dropzone.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropzone.addEventListener('drop', e => {
            e.preventDefault();
            dropzone.classList.remove('border-blue-500', 'bg-blue-50');
            const file = e.dataTransfer.files[0];
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                mostrarPreview(file);
            }
        });
    </script>

@endsection
