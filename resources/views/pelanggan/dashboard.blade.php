@extends('layouts.app')

@section('content')

<div x-data="{ open:false }" class="min-h-screen bg-[#F4F7FB] flex overflow-x-hidden relative">

    {{-- SIDEBAR DESKTOP --}}
    <aside class="hidden lg:flex flex-col fixed left-0 top-0 h-screen w-72 bg-[#005494] text-white shadow-2xl z-50">

        {{-- LOGO --}}
        <div class="px-8 py-7 border-b border-white/10">
            <img src="{{ asset('images/icon.png') }}"
                 alt="ICONNET"
                 class="h-14 w-auto brightness-0 invert">
        </div>

        {{-- USER --}}
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
            {{-- DASHBOARD (AKTIF - Putih) --}}
            <a href="{{ route('pelanggan.dashboard') }}"
               class="flex items-center gap-3 bg-white text-[#005494] px-5 py-3 rounded-2xl font-semibold shadow-md">
                <svg class="w-5 h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 12l9-9 9 9"></path>
                </svg>
                Dashboard
            </a>

            {{-- PROFIL (NON-AKTIF) --}}
            <a href="{{ route('pelanggan.profil') }}"
               class="flex items-center gap-3 px-5 py-3 rounded-2xl hover:bg-white/10 transition duration-200 text-white">
                <svg class="w-5 h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5.121 17.804A3 3 0 017.243 17h9.514a3 3 0 012.122.879M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Profil Pengguna
            </a>
            
            {{-- Logout dipindahkan ke kanan bawah --}}
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

        {{-- MOBILE NAVBAR --}}
        <div class="lg:hidden bg-[#005494] shadow-lg sticky top-0 z-50">
            <div class="px-5 py-4 flex items-center justify-between">
                <img src="{{ asset('images/icon.png') }}"
                     class="h-10 w-auto brightness-0 invert">
                <button @click="open = !open">
                    <svg class="w-7 h-7 text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            {{-- MOBILE MENU --}}
            <div x-show="open"
                 x-transition
                 class="bg-[#005494] border-t border-white/10 px-5 pb-5">
                <div class="space-y-2 pt-4">
                    <a href="{{ route('pelanggan.dashboard') }}"
                       class="block bg-white text-[#005494] px-4 py-3 rounded-xl font-semibold">
                        Dashboard
                    </a>
                    <a href="{{ route('pelanggan.profil') }}"
                       class="block text-white px-4 py-3 rounded-xl hover:bg-white/10 transition">
                        Profil Pengguna
                    </a>
                </div>
            </div>
        </div>


        {{-- CONTENT --}}
        <div class="p-5 lg:p-10">

            {{-- HERO --}}
            <div class="bg-gradient-to-r from-[#005494] to-[#006BC2] rounded-[2rem] p-7 lg:p-9 text-white shadow-lg">
                <p class="text-sm text-white/70">
                    Selamat Datang
                </p>
                <h1 class="text-lg lg:text-3xl font-semibold mt-2 leading-tight">
                    {{ auth()->user()->name }}
                </h1>
                <div class="flex flex-wrap gap-3 mt-5">
                    <div class="bg-[#00C16A] px-3 py-1.5 rounded-xl text-xs font-medium shadow">
                        ID : 1209883742
                    </div>
                    <div class="bg-white/10 px-3 py-1.5 rounded-xl text-xs backdrop-blur-sm">
                        Regional Jambi
                    </div>
                </div>
            </div>


            {{-- STATUS --}}
            <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-gray-100 mt-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl overflow-hidden border border-gray-200 shadow-sm bg-gray-100">
                        @if(auth()->user()->foto)
                            <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                                 alt="Profile"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                <svg class="w-9 h-9 text-gray-400"
                                     fill="currentColor"
                                     viewBox="0 0 24 24">
                                    <path d="M12 12c2.761 0 5-2.239 5-5S14.761 2 12 2 7 4.239 7 7s2.239 5 5 5zm0 2c-3.866 0-7 3.134-7 7h14c0-3.866-3.134-7-7-7z"/>
                                </svg>
                            </div>
                        @endif
                    </div>             
                    <div>
                        <p class="text-xs uppercase tracking-widest text-gray-400 font-semibold">
                            Status Koneksi
                        </p>
                        <h2 class="text-base lg:text-lg font-semibold text-green-600 mt-1">
                            Aktif / Normal
                        </h2>
                    </div>
                </div>
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
            </div>


            {{-- BUTTON --}}
            <a href="{{ route('pelanggan.laporan.create') }}"
               class="group block mt-6 bg-white border-2 border-dashed border-cyan-200 rounded-[2rem] p-8 lg:p-10 text-center hover:bg-cyan-50 transition-all duration-300 hover:shadow-lg">
                <div class="w-16 h-16 bg-cyan-100 rounded-[1.3rem] flex items-center justify-center mx-auto mb-4 group-hover:bg-cyan-200 transition">
                    <svg class="w-8 h-8 text-cyan-700"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="3"
                              d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h2 class="text-lg lg:text-xl font-semibold text-[#005494]">
                    Buat Laporan Gangguan
                </h2>
                <p class="text-gray-400 mt-2 text-xs lg:text-sm">
                    Klik di sini jika internet mengalami kendala
                </p>
            </a>

{{-- RIWAYAT --}}
<div class="mt-10">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-lg lg:text-xl font-semibold text-gray-800">
            Riwayat Laporan
        </h3>
    </div>

    <div class="space-y-4">
        @forelse($tickets as $ticket)
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#EEF6FF] flex items-center justify-center text-[#005494] font-bold text-sm">
                    #{{ $loop->iteration }}
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 text-sm lg:text-base">
                        {{ $ticket->nomor_tiket }} {{-- Ganti ke nomor_tiket agar sesuai DB --}}
                    </h4>
                    <p class="text-sm text-gray-400 mt-1">
                        Dibuat: {{ $ticket->created_at->format('d M Y') }}
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                {{-- BADGE STATUS (Menggunakan status_color dari Model) --}}
                <span class="px-4 py-2 rounded-xl text-[10px] font-bold uppercase text-white {{ $ticket->status_color }}">
                    {{ $ticket->status_label }}
                </span>

                {{-- TOMBOL LACAK (REVISI DISINI) --}}
                <a href="{{ route('pelanggan.laporan.track', $ticket->id) }}" 
                   class="bg-[#005494] hover:bg-[#006BC2] text-white px-5 py-2 rounded-xl text-xs font-semibold shadow-sm transition">
                   Lacak Status
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-10 border border-dashed border-gray-200 text-center">
            <p class="text-sm text-gray-400 tracking-wide">
                Belum ada riwayat laporan
            </p>
        </div>
        @endforelse
    </div>
</div>
        </div>
    </main>

    {{-- TOMBOL KELUAR DI POJOK KANAN BAWAH (FIXED) --}}
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

@endsection