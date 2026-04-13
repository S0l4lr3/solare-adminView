<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-solare-musgo flex flex-col">
        <div class="mb-10 px-4 pt-4">
            <span class="serif text-2xl text-white tracking-[3px]">SOLARE</span>
            <p class="text-[8px] tracking-[2.5px] text-white/40 uppercase">Muebles de Exterior</p>
        </div>

        <ul class="space-y-2 font-medium flex-1">
            <li>
                <a href="/dashboard"
                    class="flex items-center p-3 text-white/50 hover:text-white hover:bg-white/10 group rounded-lg transition">
                    <span class="ms-3 text-sm">Dashboard</span>
                </a>
            </li>
            <li>
                <button type="button"
                    class="flex items-center w-full p-3 text-white/50 hover:text-white hover:bg-white/10 group rounded-lg transition"
                    data-collapse-toggle="dropdown-productos">

                    <span class="flex-1 ms-3 text-sm text-left whitespace-nowrap">
                        Inventario
                    </span>

                    <!-- flecha -->
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul id="dropdown-productos" class="hidden py-2 space-y-2">
                    <li>
                        <a href="{{ route('productos.index') }}"
                            class="flex items-center w-full p-2 pl-11 text-sm text-white/50 hover:text-white hover:bg-white/10 rounded-lg">
                            Catálogo
                        </a>
                    </li>

                    <li>
                        <a href="/Inventario"
                            class="flex items-center w-full p-2 pl-11 text-sm text-white/50 hover:text-white hover:bg-white/10 rounded-lg">
                            Stock en Almacén
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('categorias.index') }}"
                            class="flex items-center w-full p-2 pl-11 text-sm text-white/50 hover:text-white hover:bg-white/10 rounded-lg">
                            Categorías
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('materiales.index') }}"
                            class="flex items-center w-full p-2 pl-11 text-sm text-white/50 hover:text-white hover:bg-white/10 rounded-lg">
                            Materiales
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('pedidos.index') }}"
                    class="flex items-center p-3 text-white/50 hover:text-white hover:bg-white/10 group rounded-lg transition">
                    <span class="ms-3 text-sm">Pedidos</span>
                </a>
            </li>
            <li>
                <button type="button"
                    class="flex items-center w-full p-3 text-white/50 hover:text-white hover:bg-white/10 group rounded-lg transition"
                    data-collapse-toggle="dropdown-Usuarios">

                    <span class="flex-1 ms-3 text-sm text-left whitespace-nowrap">
                        Usuarios
                    </span>

                    <!-- flecha -->
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul id="dropdown-Usuarios" class="hidden py-2 space-y-2">
                    <li>
                        <a href="{{ route('clientes.index') }}"
                            class="flex items-center w-full p-2 pl-11 text-sm text-white/50 hover:text-white hover:bg-white/10 rounded-lg">
                            Clientes
                        </a>
                    </li>

                    <li>
                        <a href="/usuarios"
                            class="flex items-center w-full p-2 pl-11 text-sm text-white/50 hover:text-white hover:bg-white/10 rounded-lg">
                            Empleados
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('roles.index') }}"
                            class="flex items-center w-full p-2 pl-11 text-sm text-white/50 hover:text-white hover:bg-white/10 rounded-lg">
                            Roles / Jerarquías
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="pt-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-4 py-2 text-white">
                <div class="w-8 h-8 rounded-full bg-solare-arcilla flex items-center justify-center font-bold">A</div>
                <div>
                    {{-- @dd(session('user_data')) --}}
                    <p class="text-xs font-medium">Hola, {{ session('user_data')['nombre'] }}</p>
                    <a href="/login" class="text-[10px] text-white/30 hover:text-white">Salir</a>
                </div>
            </div>
        </div>
    </div>
</aside>
