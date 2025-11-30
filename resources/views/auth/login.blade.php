<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Speed Shop System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-racing-dark { background-color: #111; }
        .text-racing-orange { color: #ff5e00; }
        .bg-racing-orange { background-color: #ff5e00; }
        /* Background Pattern ala Carbon Fiber sederhana */
        .bg-carbon {
            background-color: #1a1a1a;
            background-image: radial-gradient(#333 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="bg-carbon h-screen flex items-center justify-center font-sans text-gray-800">

    <div class="w-full max-w-md">
        
        <div class="text-center mb-8">
            <div class="inline-block p-4 rounded-full border-4 border-racing-orange bg-gray-900 mb-4 shadow-lg shadow-orange-500/20">
                <i class="fa-solid fa-wrench text-5xl text-racing-orange"></i>
            </div>
            <h1 class="text-4xl font-black text-white italic tracking-widest uppercase">
                Speed<span class="text-racing-orange">Shop</span>
            </h1>
            <p class="text-gray-400 text-sm mt-1 tracking-wide">Sparepart Management System</p>
        </div>

        <div class="bg-white rounded-lg shadow-2xl overflow-hidden border-t-4 border-racing-orange">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">OWNER ACCESS</h2>

                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-6 text-sm flex items-center gap-2" role="alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <p>{{ session('error') }}</p>
                </div>
                @endif

                <form action="{{ route('login.proses') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-bold mb-2 uppercase" for="username">
                            Username
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input class="w-full pl-10 pr-3 py-3 border-2 border-gray-200 rounded focus:outline-none focus:border-racing-orange transition-colors" 
                                id="username" name="username" type="text" placeholder="Masukan Username..." required autofocus>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-gray-700 text-sm font-bold mb-2 uppercase" for="password">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input class="w-full pl-10 pr-3 py-3 border-2 border-gray-200 rounded focus:outline-none focus:border-racing-orange transition-colors" 
                                id="password" name="password" type="password" placeholder="Masukan Password..." required>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button class="w-full bg-gray-900 hover:bg-racing-orange text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 uppercase tracking-wider shadow-lg transform active:scale-95" type="submit">
                            Masuk Sistem <i class="fa-solid fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="bg-gray-100 p-4 text-center border-t border-gray-200">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} SpeedShop Kediri. All rights reserved.</p>
            </div>
        </div>
    </div>

</body>
</html>