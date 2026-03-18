<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOLARE - @yield('title')</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />

</head>

<body class="antialiased">

    @include('components.sidebar')

    <div class="p-4 sm:ml-64">

        <header class="flex items-center justify-between mb-6 bg-white p-6 border-b border-gray-200">
            <div>
                <span
                    class="text-[10px] font-bold tracking-[2.5px] uppercase text-solare-arcilla">@yield('label')</span>
                <h1 class="serif text-3xl font-normal text-gray-900">@yield('header_title')</h1>
            </div>

            {{-- <div class="flex gap-3">
                @yield('actions')
            </div> --}}

        </header>

        <main class="px-4">
            @yield('content')
        </main>
        
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>