<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-solare-musgo flex flex-col">
      <div class="mb-10 px-4 pt-4">
         <span class="serif text-2xl text-white tracking-[3px]">SOLARE</span>
         <p class="text-[8px] tracking-[2.5px] text-white/40 uppercase">Muebles de Exterior</p>
      </div>
      
      <ul class="space-y-2 font-medium flex-1">
         <li>
            <a href="{{ route('dashboard') }}" class="flex items-center p-3 {{ request()->routeIs('dashboard') ? 'text-white bg-white/10' : 'text-white/50' }} hover:text-white hover:bg-white/10 group rounded-lg transition">
               <span class="ms-3 text-sm">Inicio</span>
            </a>
         </li>
         
         @if(session('api_token'))
            {{-- Solo se muestran si hay una sesión de red activa (Token de Sanctum) --}}
            <li>
               <a href="/Inventario" class="flex items-center p-3 {{ request()->is('Inventario*') ? 'text-white bg-white/10' : 'text-white/50' }} hover:text-white hover:bg-white/10 group rounded-lg transition">
                  <span class="ms-3 text-sm uppercase tracking-widest text-[10px] font-bold">Inventario</span>
               </a>
            </li>
            <li>
               <a href="/Ventas" class="flex items-center p-3 {{ request()->is('Ventas*') ? 'text-white bg-white/10' : 'text-white/50' }} hover:text-white hover:bg-white/10 group rounded-lg transition">
                  <span class="ms-3 text-sm uppercase tracking-widest text-[10px] font-bold">Ventas</span>
               </a>
            </li>
         @endif
      </ul>

      <div class="pt-4 border-t border-white/10">
          <div class="flex items-center gap-3 px-4 py-2 text-white">
              <div class="w-8 h-8 rounded-full bg-solare-arcilla flex items-center justify-center font-bold">
                  {{ session('user_data.nombre') ? substr(session('user_data.nombre'), 0, 1) : 'S' }}
              </div>
              <div>
                  <p class="text-xs font-medium">{{ session('user_data.nombre') ?? 'Usuario Solare' }}</p>
                  <form action="{{ route('logout') }}" method="POST">
                      @csrf
                      <button type="submit" class="text-[10px] text-white/30 hover:text-white cursor-pointer bg-transparent border-0 p-0">Finalizar Sesión</button>
                  </form>
              </div>
          </div>
      </div>
   </div>
</aside>
