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
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-wrench text-3xl text-racing-orange"></i>
                <div>
                    <h1 class="text-2xl font-bold italic tracking-wider">HUSNA OLI <span class="text-racing-orange">KEDIRI</span></h1>
                    {{-- <p class="text-xs text-gray-400">Professional Sparepart System</p> --}}
                </div>
            </div>
            <div class="flex items-center gap-6">
                
                <div class="hidden md:flex gap-4 text-sm font-bold uppercase tracking-wider">
                    <a href="{{ route('dashboard') }}" class="hover:text-racing-orange transition {{ request()->routeIs('dashboard') ? 'text-racing-orange' : 'text-gray-300' }}">Dashboard</a>
                    <a href="{{ route('items.index') }}" class="hover:text-racing-orange transition {{ request()->routeIs('items.*') ? 'text-racing-orange' : 'text-gray-300' }}">Barang</a>
                    <a href="{{ route('restock.index') }}" class="hover:text-racing-orange transition {{ request()->routeIs('restock.*') ? 'text-racing-orange' : 'text-gray-300' }}">Restock</a>
                    <a href="{{ route('laporan.index') }}" class="hover:text-racing-orange transition {{ request()->routeIs('laporan.*') ? 'text-racing-orange' : 'text-gray-300' }}">Laporan</a>
                </div>

                <div class="h-6 w-px bg-gray-600 mx-2"></div> <div class="flex gap-4 items-center">
                    <span class="text-sm hidden md:inline">Halo, {{ Auth::user()->nama ?? 'Owner' }}</span>
                    <a href="{{ route('logout') }}" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded font-bold text-sm">LOGOUT</a>
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

</body>
</html>