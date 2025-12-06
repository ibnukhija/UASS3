<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Husna Oli System</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        racing: {
                            orange: '#ff5e00',
                            dark: '#1a1a1a',
                            red: '#dc2626',
                        }}}}}
    </script>

    <style>
        .border-racing { border: 2px solid #ff5e00; }
        #nav-menu.hidden { display: none; }
        @media (min-width: 768px) {
            #nav-menu.hidden { display: flex; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">
    
    <nav class="bg-racing-dark text-white border-b-4 border-racing-orange p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center relative">
            
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-wrench text-3xl text-racing-orange"></i>
                <div>                    
                    <a href="{{ route('dashboard') }}" class="hover:opacity-80 transition">
                        <h1 class="text-2xl font-black italic tracking-wider uppercase">
                            HUSNA OLI <span class="text-racing-orange">KEDIRI</span>
                        </h1>
                    </a>
                </div>
            </div>
            
            <button id="menu-toggle" class="md:hidden text-white text-2xl p-2 focus:outline-none hover:text-racing-orange transition">
                <i class="fas fa-bars"></i>
            </button>

            <div id="nav-menu" class="hidden md:flex flex-col md:flex-row absolute md:relative top-full right-0 mt-2 md:mt-0 bg-racing-dark md:bg-transparent p-4 md:p-0 rounded-md shadow-lg md:shadow-none w-full md:w-auto border border-gray-700 md:border-none md:gap-8 items-start md:items-center">
                
                <div class="flex flex-col md:flex-row gap-2 md:gap-6 text-sm font-bold uppercase tracking-wider w-full md:w-auto">
                    <a href="{{ route('items.index') }}" class="py-2 px-2 hover:text-racing-orange transition border-b md:border-none border-gray-700 {{ request()->routeIs('items.*') ? 'text-racing-orange' : 'text-gray-300' }}">
                        Kelola Barang
                    </a>
                    <a href="{{ route('laporan.index') }}" class="py-2 px-2 hover:text-racing-orange transition border-b md:border-none border-gray-700 {{ request()->routeIs('laporan.*') ? 'text-racing-orange' : 'text-gray-300' }}">
                        Laporan
                    </a>
                    <a href="{{ route('kasir') }}" class="py-2 px-2 hover:text-racing-orange transition {{ request()->routeIs('kasir*') ? 'text-racing-orange' : 'text-gray-300' }}">
                        Kasir
                    </a>
                </div>

                <div class="h-6 w-px bg-gray-600 mx-2 hidden md:block"></div>
                
                <div class="flex flex-col md:flex-row gap-4 items-start md:items-center w-full md:w-auto pt-4 md:pt-0 border-t border-gray-700 md:border-none mt-2 md:mt-0">
                    <span class="text-sm font-semibold text-gray-300">Halo, {{ Auth::user()->nama ?? 'Owner' }}</span>
                    <a href="{{ route('logout') }}" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded font-bold text-xs self-start md:self-auto uppercase tracking-wider shadow-lg transition transform hover:scale-105">
                        LOGOUT
                    </a>
                </div>
            </div>
            
        </div>
    </nav>

    <div class="container mx-auto mt-6 p-4 grow">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow rounded flex justify-between items-center" role="alert">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <p>{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900"><i class="fa-solid fa-times"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow rounded flex justify-between items-center" role="alert">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <p>{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900"><i class="fa-solid fa-times"></i></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="bg-racing-dark text-gray-500 text-center py-4 text-xs border-t-4 border-racing-orange mt-auto">
        &copy; {{ date('Y') }} Husna Oli Kediri.
    </footer>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const navMenu = document.getElementById('nav-menu');

        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('hidden');
            navMenu.classList.toggle('flex');
        });
    </script>
</body>
</html>