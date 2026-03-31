@extends('layouts.app')

@section('title', 'Gestionar Imágenes')
@section('label', 'Gestión de Catálogo')
@section('header_title', 'Administrar Imágenes del Producto')

@section('content')

    <section class="bg-[#f7f5f2] min-h-screen p-4 sm:p-8">
        <div class="mx-auto max-w-3xl bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
            <!-- Encabezado con estilo Solare -->
            <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-6">
                <div>
                    <h2 class="serif text-3xl font-normal text-gray-900">Subir Imágenes</h2>
                    <p class="text-xs tracking-widest text-solare-arcilla uppercase mt-1">
                        Producto: {{ $producto['nombre'] ?? 'Cargando...' }}
                    </p>
                </div>
                @if(isset($producto['id']))
                    <span class="px-3 py-1 text-[10px] font-bold text-solare-musgo bg-solare-musgo/10 rounded-full uppercase tracking-tighter">
                        ID: #{{ $producto['id'] }}
                    </span>
                @endif
            </div>

            <form action="{{ route('imagenes.store', $producto['id'] ?? '') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto['id'] ?? '' }}">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg border border-green-100 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 rounded-lg border border-red-100">
                        <ul class="text-xs text-red-600 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Dropzone con colores Solare -->
                    <div class="group border-2 border-dashed border-gray-200 rounded-xl p-10 text-center transition-all hover:border-solare-musgo hover:bg-gray-50 cursor-pointer" id="dropzone">
                        <div id="dropzone-placeholder" class="flex flex-col items-center">
                            <div class="w-16 h-16 mb-4 rounded-full bg-solare-musgo/5 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-solare-musgo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-lg font-medium text-gray-700">Arrastra tus imágenes aquí</p>
                            <p class="text-xs text-solare-arcilla uppercase tracking-widest mt-1">O haz clic para buscar archivos</p>
                        </div>
                        
                        <div id="preview-container" class="hidden grid grid-cols-2 gap-4 mt-4">
                            <!-- Previsualización dinámica -->
                        </div>

                        <input type="file" name="imagenes[]" id="file-input" class="hidden" multiple accept="image/*">
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <input type="checkbox" name="es_principal" id="es_principal" class="w-4 h-4 text-solare-musgo bg-white border-gray-300 rounded focus:ring-solare-musgo outline-none">
                        <label for="es_principal" class="ml-3 text-sm font-medium text-gray-700">Establecer estas imágenes como principales</label>
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
                    <button type="submit" class="px-8 py-3 text-sm font-bold text-white bg-solare-musgo rounded-lg hover:bg-opacity-90 transition-all shadow-md uppercase tracking-widest">
                        Subir a la Galería
                    </button>
                    <a href="{{ route('productos.index') }}" class="px-8 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all uppercase tracking-widest">
                        Volver
                    </a>
                </div>
            </form>

            <!-- Galería de imágenes actuales con estilo de tarjetas -->
            <div class="mt-16">
                <h3 class="serif text-2xl text-gray-900 mb-6">Galería Actual</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                    @forelse($imagenes as $img)
                        <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-gray-50">
                            <img src="{{ $img['full_image_url'] }}" class="w-full h-full object-cover">
                            
                            <!-- Overlay de acciones -->
                            <div class="absolute inset-0 bg-solare-musgo/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-3 backdrop-blur-[2px]">
                                <form action="{{ route('imagenes.destroy', $img['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-white text-red-600 rounded-full hover:bg-red-50 transition-colors shadow-lg" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                
                                @if(!($img['es_principal'] ?? false))
                                    <form action="{{ route('imagenes.principal', $img['id']) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-2.5 bg-white text-solare-musgo rounded-full hover:bg-solare-arcilla/10 transition-colors shadow-lg" title="Hacer principal">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @if($img['es_principal'] ?? false)
                                <div class="absolute top-2 left-2 px-2 py-1 bg-solare-musgo text-white text-[8px] font-bold uppercase tracking-widest rounded shadow-sm">
                                    Principal
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full border-2 border-dashed border-gray-100 rounded-xl py-12 text-center">
                            <p class="text-sm text-gray-400 italic">Este producto aún no tiene imágenes adicionales.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file-input');
        const previewContainer = document.getElementById('preview-container');
        const placeholder = document.getElementById('dropzone-placeholder');

        dropzone.onclick = () => fileInput.click();

        fileInput.onchange = (e) => handleFiles(e.target.files);

        dropzone.ondragover = (e) => {
            e.preventDefault();
            dropzone.classList.add('border-solare-musgo', 'bg-solare-musgo/5');
        };

        dropzone.ondragleave = () => {
            dropzone.classList.remove('border-solare-musgo', 'bg-solare-musgo/5');
        };

        dropzone.ondrop = (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-solare-musgo', 'bg-solare-musgo/5');
            handleFiles(e.dataTransfer.files);
        };

        function handleFiles(files) {
            previewContainer.innerHTML = '';
            previewContainer.classList.remove('hidden');
            placeholder.classList.add('hidden');

            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-video rounded-lg overflow-hidden border border-gray-200 shadow-sm';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>

@endsection
