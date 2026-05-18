<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLN Icon Plus - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-stretch overflow-hidden font-sans">

    <div class="hidden lg:flex lg:w-1/2 bg-[#004a80] flex-col justify-center p-16 relative">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute -top-20 -left-20 w-96 h-96 rounded-full border border-white"></div>
            <div class="absolute bottom-20 right-10 w-64 h-64 rounded-full border border-white"></div>
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-12">
                <div class="bg-white p-2 rounded-xl">
                    <img src="{{ asset('images/icon.png') }}" alt="Iconnet" class="h-10">
                </div>
                <div>
                    <h2 class="text-white font-bold text-xl leading-none">ICONPLUS</h2>
                    <p class="text-cyan-400 text-[10px] tracking-widest uppercase font-semibold">Regional Jambi</p>
                </div>
            </div>

            <h1 class="text-white text-6xl font-extrabold leading-[1.1] mb-6">
                FTTH <span class="text-green-400 italic">Self-</span><br>Reporting
            </h1>
            <div class="w-20 h-1.5 bg-green-400 mb-8"></div>
            <p class="text-blue-100 text-lg leading-relaxed max-w-md opacity-90">
                Portal manajemen gangguan infrastruktur fiber optik yang cepat, transparan, dan terintegrasi.
            </p>
        </div>
    </div>

    <div class="w-full lg:w-1/2 bg-white flex flex-col justify-center items-center p-8 md:p-16">
        
        <div class="w-full max-w-md">
            <div class="text-center mb-10">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/icon.png') }}" 
                         alt="PLN ICON PLUS" 
                         class="h-16 w-auto object-contain">
                </div>
                <h2 class="text-gray-800 text-3xl font-bold tracking-tight">Login Portal</h2>
                <p class="text-gray-500 text-sm mt-2">Gunakan ID Pelanggan atau Akun Pegawai Anda</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Username / ID Pelanggan</label>
                    <input type="text" name="email" value="{{ old('email') }}" 
                           placeholder="Masukkan ID Anda"
                           class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 text-gray-700 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
                           required autofocus>
                    @error('email')
                        <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Password</label>
                    <input type="password" name="password"
                           placeholder="••••••••"
                           class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 text-gray-700 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
                           required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-[#004a80] hover:bg-[#00365d] text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-900/20 active:scale-[0.98] transition-all uppercase tracking-widest text-sm">
                    Masuk 
                </button>
            </form>

            <div class="text-center mt-12">
                <p class="text-gray-400 text-[10px] tracking-[0.3em] uppercase font-bold">Supported By</p>
                <p class="text-gray-600 text-sm font-bold mt-1 tracking-wide">IT Regional Jambi</p>
            </div>
        </div>
    </div>

</body>
</html>