<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Husna Oli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-racing-dark { background-color: #1a1a1a; }
        .bg-racing-red { background-color: #dc2626; }
        .bg-racing-orange { background-color: #f97316; }
        .border-racing { border: 2px solid #f97316; }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-racing-dark text-white border-b-4 border-racing p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center relative">
            
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-wrench text-3xl text-racing-orange"></i>
                <div>
                    <h1 class="text-2xl font-bold italic tracking-wider">HUSNA OLI <span class="text-racing-orange">KEDIRI</span></h1>
                </div>
            </div>
            
            <button id="menu-toggle" class="md:hidden text-white text-2xl p-2 focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>

            <div id="nav-menu" class="hidden md:flex flex-col md:flex-row absolute md:relative top-full right-0 mt-2 md:mt-0 bg-racing-dark md:bg-transparent p-4 md:p-0 rounded-md shadow-lg md:shadow-none z-10 border border-gray-700 md:border-none md:gap-6 items-start md:items-center">
                
                <div class="flex flex-col md:flex-row gap-4 text-sm font-bold uppercase tracking-wider mb-4 md:mb-0">
                    <a href="{{ route('dashboard') }}" class="py-1 px-2 hover:text-racing-orange transition {{ request()->routeIs('dashboard') ? 'text-racing-orange' : 'text-gray-300' }}">Dashboard</a>
                    <a href="{{ route('items.index') }}" class="py-1 px-2 hover:text-racing-orange transition {{ request()->routeIs('items.*') ? 'text-racing-orange' : 'text-gray-300' }}">Barang</a>
                    <a href="{{ route('restock.index') }}" class="py-1 px-2 hover:text-racing-orange transition {{ request()->routeIs('restock.*') ? 'text-racing-orange' : 'text-gray-300' }}">Restock</a>
                    <a href="{{ route('laporan.index') }}" class="py-1 px-2 hover:text-racing-orange transition {{ request()->routeIs('laporan.*') ? 'text-racing-orange' : 'text-gray-300' }}">Laporan</a>
                </div>

                <div class="h-6 w-px bg-gray-600 mx-2 hidden md:block"></div>
                
                <div class="flex flex-col md:flex-row gap-4 items-start md:items-center w-full md:w-auto pt-4 md:pt-0 border-t border-gray-700 md:border-none">
                    <span class="text-sm">Halo, {{ Auth::user()->nama ?? 'Owner' }}</span>
                    <a href="{{ route('logout') }}" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded font-bold text-sm self-start md:self-auto">LOGOUT</a>
                </div>
            </div>
            
        </div>
    </nav>

    <div class="container mx-auto mt-6 p-4">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @yield('content')
    </div>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            var menu = document.getElementById('nav-menu');
            // Toggle class 'hidden' untuk menampilkan/menyembunyikan menu
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>