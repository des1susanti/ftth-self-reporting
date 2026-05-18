@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#F4F7FB] flex overflow-x-hidden relative">

    {{-- SIDEBAR --}}
    <aside class="hidden lg:flex flex-col fixed left-0 top-0 h-screen w-72 bg-[#1E5299] text-white shadow-2xl z-50">

        {{-- LOGO (DIPERBESAR) --}}
        <div class="px-8 py-10 border-b border-white/10 flex justify-center">
            <img src="{{ asset('images/icon.png') }}"
                 alt="ICONNET"
                 class="h-20 w-auto brightness-0 invert object-contain">
        </div>

        {{-- USER INFO --}}
        <div class="px-8 py-8">
            <p class="text-white/70 text-sm">Selamat Datang</p>
            <h1 class="text-2xl font-bold mb-4">
                {{ auth()->user()->name }}
            </h1>

            {{-- ID DISESUAIKAN DENGAN DASHBOARD --}}
            <div class="inline-block bg-[#5CB85C] text-white px-4 py-1.5 rounded-xl font-bold text-sm mb-2">
                ID : 1209883742
            </div>
            
            <p class="text-white/60 text-sm">
                Regional Jambi
            </p>
        </div>

        {{-- MENU --}}
        <nav class="px-5 space-y-4">
            <a href="{{ route('pelanggan.dashboard') }}"
               class="flex items-center gap-4 px-6 py-3 rounded-2xl hover:bg-white/10 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('pelanggan.profil') }}"
               class="flex items-center justify-between bg-white text-[#1E5299] px-6 py-4 rounded-2xl font-bold shadow-lg">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Pengguna
                </span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
            </a>
            
            {{-- Menu Keluar di sini sudah dihapus --}}
        </nav>

        {{-- FOOTER SIDEBAR --}}
        <div class="mt-auto px-8 py-6 border-t border-white/10">
            <p class="text-[11px] tracking-[0.2em] uppercase text-white/40 font-medium">
                Self Reporting System
            </p>
        </div>
    </aside>


    {{-- MAIN --}}
    <main class="flex-1 lg:ml-72 p-5 lg:p-10 pb-24"> {{-- Tambah padding bawah agar tidak tertutup tombol logout --}}
        <div class="max-w-5xl mx-auto space-y-8">

            {{-- PROFILE CARD --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-[#005494] to-[#0B6FCF] px-8 py-10 text-white relative">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                        <div class="flex items-center gap-6">
                            <div class="relative">
                                @if(auth()->user()->foto)
                                    <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                                         class="w-28 h-28 rounded-full object-cover border-4 border-white/30 shadow-2xl">
                                @else
                                    <div class="w-28 h-28 rounded-full bg-white/20 flex items-center justify-center border-4 border-white/30 shadow-2xl">
                                        <span class="text-4xl font-bold">PL</span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold">{{ auth()->user()->name }}</h1>
                                <p class="text-white/70">{{ auth()->user()->email }}</p>
                                <div class="mt-3 inline-block bg-black/10 px-3 py-1 rounded-lg text-xs font-semibold">
                                    ID Pelanggan : 1209883742
                                </div>
                            </div>
                        </div>

                        <button onclick="toggleEdit()"
                                class="bg-white text-[#005494] px-6 py-2.5 rounded-xl font-bold shadow-lg hover:bg-gray-100 transition self-start md:self-center">
                            + Edit Profil
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    @if(session('success'))
                        <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div id="profileInfo" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-2xl p-5">
                            <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                            <p class="font-bold text-gray-800">{{ auth()->user()->name }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-5">
                            <p class="text-sm text-gray-500 mb-1">Email</p>
                            <p class="font-bold text-gray-800">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-5">
                            <p class="text-sm text-gray-500 mb-1">Nomor Telepon</p>
                            <p class="font-bold text-gray-800">{{ auth()->user()->phone ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-5">
                            <p class="text-sm text-gray-500 mb-1">Alamat</p>
                            <p class="font-bold text-gray-800">{{ auth()->user()->alamat ?? '-' }}</p>
                        </div>
                    </div>

                    <div id="editForm" class="hidden">
                        <form method="POST" action="{{ route('pelanggan.profil.update') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold mb-2">Foto Profil</label>
                                    <input type="file" name="foto" class="w-full border rounded-xl px-4 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full border rounded-xl px-4 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-2">Email</label>
                                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full border rounded-xl px-4 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-2">Nomor Telepon</label>
                                    <input type="text" name="phone" value="{{ auth()->user()->phone }}" class="w-full border rounded-xl px-4 py-2 text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold mb-2">Alamat</label>
                                    <textarea name="alamat" rows="2" class="w-full border rounded-xl px-4 py-2 text-sm">{{ auth()->user()->alamat }}</textarea>
                                </div>
                            </div>
                            <div class="flex gap-3 pt-4">
                                <button type="submit" class="bg-[#005494] text-white px-6 py-2 rounded-xl font-bold">Simpan</button>
                                <button type="button" onclick="toggleEdit()" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-xl font-bold">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MENU BAWAH --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('password.edit') }}"
                   class="flex items-center gap-5 bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-2xl">🔒</div>
                    <div>
                        <h3 class="font-bold text-gray-800">Ubah Password</h3>
                        <p class="text-xs text-gray-400">Ganti password untuk keamanan akun</p>
                    </div>
                </a>

                <a href="https://wa.me/6282380695507" target="_blank"
                   class="flex items-center gap-5 bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 flex items-center justify-center text-2xl">💬</div>
                    <div>
                        <h3 class="font-bold text-gray-800">Bantuan Layanan</h3>
                        <p class="text-xs text-gray-400">Hubungi customer service kami</p>
                    </div>
                </a>
            </div>
        </div>
    </main>

    {{-- TOMBOL LOGOUT SEBELAH KANAN BAWAH --}}
    <div class="fixed bottom-8 right-8 z-50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="group flex items-center gap-3 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-2xl font-bold shadow-2xl transition-all hover:scale-105 active:scale-95">
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