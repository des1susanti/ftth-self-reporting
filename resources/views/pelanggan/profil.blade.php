@extends('layouts.app')

@section('content')

<div x-data="{ open:false }" class="min-h-screen bg-[#F4F7FB] flex overflow-x-hidden relative">

  {{-- SIDEBAR DESKTOP --}}
<aside class="hidden lg:flex flex-col fixed left-0 top-0 h-screen w-72 bg-[#005494] text-white shadow-2xl z-50">

    {{-- LOGO - DENGAN FORCE CSS AGAR TIDAK TURUN --}}
    <div class="px-8 border-b border-white/10 flex items-center" style="height: 110px !important; min-height: 110px !important;">
        <img src="{{ asset('images/icon.png') }}"
             alt="ICONNET"
             style="height: 56px !important; width: auto !important; margin-top: -5px !important;"
             class="brightness-0 invert">
    </div>
        {{-- USER }}
        <div class="px-8 py-8">
            <p class="text-sm text-white/60">
                Selamat Datang
            </p>

            <h1 class="text-xl font-semibold mt-2 leading-snug">
                {{ auth()->user()->name }}
            </h1>

            <div class="mt-6">
                <div class="inline-flex items-center bg-[#00C16A] px-4 py-2 rounded-xl text-xs font-semibold shadow">
                    ID : 1209883742
                </div>

                <p class="text-sm text-white/60 mt-4">
                    Regional Jambi
                </p>
            </div>
        </div>

        {{-- MENU --}}
        <nav class="px-5 space-y-3">
            {{-- Dashboard (Non-Aktif/Transparan) --}}
            <a href="{{ route('pelanggan.dashboard') }}"
               class="flex items-center gap-3 px-5 py-3 rounded-2xl hover:bg-white/10 transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9"></path>
                </svg>
                <span class="font-semibold">Dashboard</span>
            </a>

            {{-- Profil (Aktif - Warna Putih Sesuai Style Dashboard) --}}
            <a href="{{ route('pelanggan.profil') }}"
               class="flex items-center gap-3 bg-white text-[#005494] px-5 py-3 rounded-2xl font-semibold shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A3 3 0 017.243 17h9.514a3 3 0 012.122.879M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Profil Pengguna
            </a>
            
            {{-- Tombol Keluar dihapus dari Sidebar sesuai permintaan --}}
        </nav>

        {{-- FOOTER --}}
        <div class="mt-auto px-8 py-6 border-t border-white/10">
            <p class="text-[11px] tracking-[0.25em] uppercase text-white/40">
                Self Reporting System
            </p>
        </div>
    </aside>


    {{-- MAIN --}}
    <main class="flex-1 lg:ml-72 w-full pb-24">

        {{-- MOBILE NAVBAR (Identik Dashboard) --}}
        <div class="lg:hidden bg-[#005494] shadow-lg sticky top-0 z-50">
            <div class="px-5 py-4 flex items-center justify-between">
                <img src="{{ asset('images/icon.png') }}" class="h-10 w-auto brightness-0 invert">
                <button @click="open = !open">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <div x-show="open" x-transition class="bg-[#005494] border-t border-white/10 px-5 pb-5">
                <div class="space-y-2 pt-4">
                    <a href="{{ route('pelanggan.dashboard') }}" class="block text-white px-4 py-3 rounded-xl hover:bg-white/10 transition">
                        Dashboard
                    </a>
                    <a href="{{ route('pelanggan.profil') }}" class="block bg-white text-[#005494] px-4 py-3 rounded-xl font-semibold">
                        Profil Pengguna
                    </a>
                </div>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="p-5 lg:p-10">
            
            {{-- CARD PROFIL HEADER --}}
            <div class="bg-gradient-to-r from-[#005494] to-[#006BC2] rounded-[2rem] p-7 lg:p-9 text-white shadow-lg mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white/20 shadow-xl bg-white/10">
                            @if(auth()->user()->foto)
                                <img src="{{ asset('storage/' . auth()->user()->foto) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-xl lg:text-3xl font-semibold leading-tight">{{ auth()->user()->name }}</h1>
                            <p class="text-white/70 text-sm mt-1">{{ auth()->user()->email }}</p>
                            <div class="mt-4 bg-[#00C16A] inline-block px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                ID : 1209883742
                            </div>
                        </div>
                    </div>
                    <button onclick="toggleEdit()" class="bg-white text-[#005494] px-6 py-2.5 rounded-2xl font-bold shadow-lg hover:bg-gray-100 transition active:scale-95">
                        + Edit Profil
                    </button>
                </div>
            </div>

            {{-- INFORMASI DETAIL --}}
            <div id="profileInfo" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</p>
                    <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                </div>
                <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email</p>
                    <p class="font-semibold text-gray-800">{{ auth()->user()->email }}</p>
                </div>
                <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nomor Telepon</p>
                    <p class="font-semibold text-gray-800">{{ auth()->user()->phone ?? '-' }}</p>
                </div>
                <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Alamat</p>
                    <p class="font-semibold text-gray-800">{{ auth()->user()->alamat ?? '-' }}</p>
                </div>
            </div>

            {{-- FORM EDIT (Hidden) --}}
            <div id="editForm" class="hidden bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <form method="POST" action="{{ route('pelanggan.profil.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Profil</label>
                            <input type="file" name="foto" class="w-full border-2 border-dashed rounded-2xl px-4 py-3 text-sm outline-none focus:border-[#005494]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full border rounded-2xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#005494]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon</label>
                            <input type="text" name="phone" value="{{ auth()->user()->phone }}" class="w-full border rounded-2xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#005494]/20">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <textarea name="alamat" rows="2" class="w-full border rounded-2xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#005494]/20">{{ auth()->user()->alamat }}</textarea>
                        </div>
                    </div>
                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="bg-[#005494] text-white px-8 py-3 rounded-2xl font-bold hover:bg-[#00457C]">Simpan Perubahan</button>
                        <button type="button" onclick="toggleEdit()" class="bg-gray-100 text-gray-600 px-8 py-3 rounded-2xl font-bold hover:bg-gray-200">Batal</button>
                    </div>
                </form>
            </div>

            {{-- MENU PENGATURAN LAIN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <a href="{{ route('password.edit') }}" class="flex items-center gap-5 bg-white p-7 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-2xl group-hover:scale-110 transition">🔒</div>
                    <div>
                        <h3 class="font-bold text-gray-800">Ubah Password</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Keamanan akun pelanggan</p>
                    </div>
                </a>
                <a href="https://wa.me/6282380695507" target="_blank" class="flex items-center gap-5 bg-white p-7 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 flex items-center justify-center text-2xl group-hover:scale-110 transition">💬</div>
                    <div>
                        <h3 class="font-bold text-gray-800">Bantuan Layanan</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Hubungi CS ICONNET</p>
                    </div>
                </a>
            </div>
        </div>
    </main>

    {{-- TOMBOL KELUAR (Fixed di pojok kanan bawah - Sesuai permintaan) --}}
    <div class="fixed bottom-8 right-8 z-[60]">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="group flex items-center gap-3 bg-red-500 hover:bg-red-600 text-white px-7 py-4 rounded-[2rem] font-bold shadow-2xl transition-all hover:scale-105 active:scale-95">
                <span>Keluar</span>
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
    function toggleEdit() {
        const info = document.getElementById('profileInfo');
        const form = document.getElementById('editForm');
        info.classList.toggle('hidden');
        form.classList.toggle('hidden');
    }
</script>

@endsection