@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#F4F7FB] flex overflow-x-hidden">

    {{-- SIDEBAR --}}
    <aside class="hidden lg:flex flex-col fixed left-0 top-0 h-screen w-72 bg-[#005494] text-white shadow-2xl z-50">

        {{-- LOGO --}}
        <div class="px-8 py-7 border-b border-white/10">

            <img src="{{ asset('images/icon.png') }}"
                 alt="ICONNET"
                 class="h-14 w-auto brightness-0 invert">
        </div>

        {{-- USER --}}
        <div class="px-8 py-8 text-center">

            @if(auth()->user()->foto)

                <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                     class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg mx-auto">

            @else

                <div class="w-28 h-28 rounded-full bg-white/20 flex items-center justify-center border-4 border-white shadow-lg mx-auto">

                    <svg class="w-14 h-14 text-white"
                         fill="currentColor"
                         viewBox="0 0 24 24">

                        <path d="M12 12c2.761 0 5-2.239 5-5S14.761 2 12 2 7 4.239 7 7s2.239 5 5 5zm0 2c-3.866 0-7 3.134-7 7h14c0-3.866-3.134-7-7-7z"/>
                    </svg>
                </div>

            @endif

            <h1 class="text-xl font-semibold mt-5">
                {{ auth()->user()->name }}
            </h1>

            <p class="text-white/60 text-sm mt-2">
                {{ auth()->user()->email }}
            </p>
        </div>

        {{-- MENU --}}
        <nav class="px-5 space-y-3">

            <a href="{{ route('pelanggan.dashboard') }}"
               class="flex items-center gap-3 px-5 py-3 rounded-2xl hover:bg-white/10 transition">

                🏠 Dashboard
            </a>

            <a href="{{ route('pelanggan.profil') }}"
               class="flex items-center gap-3 bg-white text-[#005494] px-5 py-3 rounded-2xl font-semibold shadow-md">

                👤 Profil Saya
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                        class="w-full text-left flex items-center gap-3 px-5 py-3 rounded-2xl hover:bg-red-500/20 transition">

                    🚪 Keluar
                </button>
            </form>
        </nav>
    </aside>


    {{-- MAIN --}}
    <main class="flex-1 lg:ml-72 p-5 lg:p-10">

        <div class="max-w-2xl mx-auto bg-white rounded-[2rem] shadow-lg p-8">

            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-8">

                <h1 class="text-3xl font-bold text-[#005494]">
                    Ubah Password
                </h1>

                {{-- CLOSE --}}
                <a href="{{ route('pelanggan.profil') }}"
                   class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-xl transition">

                    ✕
                </a>
            </div>

            {{-- SUCCESS --}}
            @if(session('success'))

                <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-5">

                    {{ session('success') }}
                </div>

            @endif

            {{-- ERROR --}}
            @if(session('error'))

                <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-5">

                    {{ session('error') }}
                </div>

            @endif

            {{-- FORM --}}
           <form method="POST"
      action="{{ route('password.update') }}">
  @csrf
    @method('PUT')
    
                {{-- PASSWORD LAMA --}}
                <div class="mb-6">

                    <label class="block text-sm font-semibold mb-2">
                        Password Lama
                    </label>

                    <input type="password"
                           name="current_password"
                           required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- PASSWORD BARU --}}
                <div class="mb-6">

                    <label class="block text-sm font-semibold mb-2">
                        Password Baru
                    </label>

                    <input type="password"
                           name="password"
                           required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- KONFIRMASI --}}
                <div class="mb-8">

                    <label class="block text-sm font-semibold mb-2">
                        Konfirmasi Password
                    </label>

                    <input type="password"
                           name="password_confirmation"
                           required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- BUTTON --}}
                <div class="flex gap-4">

                    <button type="submit"
                            class="bg-[#005494] hover:bg-[#00457C] text-white px-8 py-4 rounded-2xl font-semibold transition shadow-lg">

                        Simpan Password
                    </button>

                    <a href="{{ route('pelanggan.profil') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-4 rounded-2xl font-semibold transition">

                        Batal
                    </a>
                </div>

            </form>
        </div>
    </main>
</div>

@endsection