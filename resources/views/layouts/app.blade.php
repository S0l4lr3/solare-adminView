<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOLARE - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f7f5f2; }
        .serif { font-family: 'Cormorant Garamond', serif; }
        .text-solare-arcilla { color: #958174; }
        .bg-solare-musgo { background-color: #50594e; }
        .bg-solare-arcilla { background-color: #958174; }
    </style>
</head>
<body class="antialiased">

    @include('components.sidebar')

    <div class="p-4 sm:ml-64">
        <header class="flex items-center mb-6 bg-white p-6 border-b border-gray-200">
            <button onclick="document.getElementById('logo-sidebar').classList.toggle('-translate-x-full')" class="sm:hidden mr-4 p-2 text-gray-600 hover:bg-gray-100 rounded-lg border border-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div class="flex-1 flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-bold tracking-[2.5px] uppercase text-solare-arcilla">@yield('label')</span>
                    <h1 class="serif text-3xl font-normal text-gray-900">@yield('header_title')</h1>
                </div>
                <div class="flex gap-3">
                    @yield('actions')
                </div>
            </div>
        </header>

        <main class="px-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>