@extends('layouts.app')

@section('title', 'Imágenes del producto')
@section('label', 'Panel General')
@section('header_title', 'Bienvenido, Admin')

@section('content')
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-3xl lg:py-16">

            {{-- Encabezado --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Imágenes: {{ $producto->nombre }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Gestiona las imágenes del producto</p>
                </div>
                <a href="{{ route('productos.edit', $producto->id) }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    ← Volver a editar
                </a>
            </div>

            {{-- Mensajes de éxito --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 rounded-lg">
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Galería de imágenes actuales --}}
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">
                    Imágenes actuales ({{ $producto->imagenes->count() }})
                </h3>

                @if ($producto->imagenes->isEmpty())
                    <p class="text-sm text-gray-400 italic">Este producto no tiene imágenes aún.</p>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach ($producto->imagenes->sortBy('orden') as $imagen)
                            <div
                                class="relative group border rounded-lg overflow-hidden
                            {{ $imagen->es_principal ? 'border-blue-500 ring-2 ring-blue-400' : 'border-gray-200 dark:border-gray-600' }}">

                                {{-- Imagen --}}
                                <img src="{{ asset('storage/' . $imagen->url) }}" class="w-full h-40 object-cover"
                                    alt="Imagen producto">

                                {{-- Badge principal --}}
                                @if ($imagen->es_principal)
                                    <span
                                        class="absolute top-2 left-2 bg-blue-600 text-white text-xs font-semibold px-2 py-0.5 rounded">
                                        Principal
                                    </span>
                                @endif

                                {{-- Botones sobre la imagen --}}
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-between p-2 gap-2">

                                    {{-- Marcar como principal --}}
                                    @if (!$imagen->es_principal)
                                        <form action="{{ route('productos.imagenes.principal', $imagen->id) }}"
                                            method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded">
                                                ★ Principal
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-white opacity-60">Principal</span>
                                    @endif

                                    {{-- Eliminar --}}
                                    <form action="{{ route('productos.imagenes.destroy', $imagen->id) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar esta imagen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-xs bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">
                                            🗑 Borrar
                                        </button>
                                    </form>

                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Formulario para subir nuevas imágenes --}}
            <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-5">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 uppercase tracking-wide">
                    Subir nuevas imágenes
                </h3>

                <form action="{{ route('productos.imagenes.store', $producto->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Dropzone múltiple --}}
                    <div id="dropzone"
                        class="flex flex-col items-center justify-center w-full min-h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:border-gray-600 transition-colors p-4">

                        {{-- Previews de imágenes seleccionadas --}}
                        <div id="preview-grid" class="hidden w-full grid grid-cols-3 gap-3 mb-4"></div>

                        <div id="dropzone-placeholder" class="flex flex-col items-center justify-center py-4">
                            <svg class="w-8 h-8 mb-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2" />
                            </svg>
                            <p class="text-sm text-gray-500 mb-1">
                                <span class="font-semibold">Click para seleccionar</span> o arrastra aquí
                            </p>
                            <p class="text-xs text-gray-400">PNG, JPG — Puedes seleccionar varias a la vez (máx. 2MB c/u)
                            </p>
                        </div>

                        <input id="dropzone-file" type="file" name="imagenes[]" class="hidden"
                            accept="image/png, image/jpeg" multiple />
                    </div>

                    <p id="selected-count" class="text-xs text-gray-500 mt-2 hidden"></p>

                    <div class="flex gap-3 mt-4">
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Subir imágenes
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('dropzone-file');
        const previewGrid = document.getElementById('preview-grid');
        const placeholder = document.getElementById('dropzone-placeholder');
        const counter = document.getElementById('selected-count');

        function mostrarPreviews(files) {
            previewGrid.innerHTML = '';

            if (files.length === 0) {
                previewGrid.classList.add('hidden');
                placeholder.classList.remove('hidden');
                counter.classList.add('hidden');
                return;
            }

            previewGrid.classList.remove('hidden');
            placeholder.classList.add('hidden');
            counter.classList.remove('hidden');
            counter.textContent = `${files.length} imagen(es) seleccionada(s)`;

            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-28 object-cover rounded border border-gray-200';
                    previewGrid.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }

        dropzone.addEventListener('click', e => {
            e.stopPropagation();
            fileInput.click();
        });

        fileInput.addEventListener('change', () => mostrarPreviews(fileInput.files));

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
            const dt = new DataTransfer();
            Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
            fileInput.files = dt.files;
            mostrarPreviews(fileInput.files);
        });
    </script>
@endsection
