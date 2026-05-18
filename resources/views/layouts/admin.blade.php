<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLN Icon Plus - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex bg-gray-50 overflow-x-hidden">

    <!-- Sidebar -->
    <div class="w-60 min-h-screen bg-blue-900 flex flex-col fixed left-0 top-0 z-20">
        
        <!-- Logo -->
       <div class="w-full flex items-center justify-center p-4 mb-6 bg-slate-900/20 border-b border-slate-700/30 h-20">
    <img src="{{ asset('images/icon.png') }}" 
         alt="Logo Iconnet" 
         class="w-full h-auto max-w-[150px] object-contain filter brightness-0 invert">
</div>

        <!-- Menu -->
        <nav class="flex-1 p-4 space-y-1">
            <p class="text-blue-500 text-xs uppercase tracking-widest px-3 mb-3 mt-2">Menu Utama</p>
            
            <a href="{{ auth()->user()->role == 'teknisi' ? route('teknisi.dashboard') : route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('admin.dashboard') || request()->routeIs('teknisi.dashboard') 
                  ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                <span class="text-lg">📊</span>
                <span>Dashboard</span>
            </a>

            <a href="{{ auth()->user()->role == 'teknisi' ? route('teknisi.laporan') : route('admin.laporan') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('admin.laporan') || request()->routeIs('teknisi.laporan')
                  ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                <span class="text-lg">📋</span>
                <span>Data Gangguan</span>
            </a>

            @if(auth()->user()->role != 'teknisi')
            <a href="{{ route('admin.users') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('admin.users*') 
                  ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                <span class="text-lg">👥</span>
                <span>Manajemen User</span>
            </a>
            @endif

            <p class="text-blue-500 text-xs uppercase tracking-widest px-3 mb-3 mt-6">Akun</p>

            <a href="{{ route('pelanggan.profil') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('pelanggan.profil') 
                  ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                <span class="text-lg">👤</span>
                <span>Profil Saya</span>
            </a>
        </nav>

        <!-- User Info & Logout -->
        <div class="p-4 border-t border-blue-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-blue-700 flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-blue-400 text-xs capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left text-red-400 hover:text-red-300 text-xs px-2 py-1.5 rounded-lg hover:bg-blue-800 transition flex items-center gap-2">
                    <span>🚪</span> Keluar
                </button>
            </form>
            <p class="text-blue-600 text-xs text-center mt-3 uppercase tracking-wider">Regional Jambi</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="ml-60 flex-1 flex flex-col min-h-screen">

        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-10 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-gray-800">@yield('page-title')</h1>
                <p class="text-xs text-gray-400 uppercase tracking-wider mt-0.5">@yield('page-subtitle')</p>
            </div>
            <div class="flex items-center gap-4">
                <!-- Notifikasi -->
                <div class="relative">
                    <button id="notif-btn" class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                        <span>🔔</span>
                    </button>
                    @php $pending = \App\Models\Ticket::where('status','pending')->count(); @endphp
                    @if($pending > 0)
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                        {{ $pending }}
                    </span>
                    @endif
                </div>
                <!-- User -->
                <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-2">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-green-500 font-medium">● ONLINE</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center">
                        <span class="text-white font-bold text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Message -->
        @if(session('success'))
        <div class="mx-8 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mx-8 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <span>❌</span> {{ session('error') }}
        </div>
        @endif

        <!-- Page Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="px-8 py-4 border-t border-gray-100 text-xs text-gray-400 text-center">
            © {{ date('Y') }} PLN Icon Plus — FTTH Self Reporting System · Regional Jambi
        </footer>
    </div>
<script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mencari semua dropdown teknisi di dalam halaman
            const selectInputs = document.querySelectorAll('.teknisi-select');

            selectInputs.forEach(select => {
                select.addEventListener('change', function () {
                    // Mencari baris (container) tempat dropdown berada
                    const parent = this.closest('div');
                    const button = parent.querySelector('.btn-tugaskan');

                    if (this.value !== "") {
                        // Jika teknisi dipilih: Aktifkan tombol & ubah jadi Biru
                        button.disabled = false;
                        button.classList.remove('bg-gray-400', 'cursor-not-allowed');
                        button.classList.add('bg-blue-600', 'hover:bg-blue-700', 'shadow-md');
                    } else {
                        // Jika kembali ke default: Matikan tombol & ubah jadi Abu-abu
                        button.disabled = true;
                        button.classList.add('bg-gray-400', 'cursor-not-allowed');
                        button.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'shadow-md');
                    }
                });
            });
        });
    </script>
</body>
</html>

</body>
</html>