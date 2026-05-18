@extends('layouts.admin')

@section('title', 'Profil')
@section('page-title', 'Profil Saya')

@section('page-subtitle')
    Informasi Akun {{ ucfirst(auth()->user()->role) }}
@endsection

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- HEADER CARD (TETAP SAMA) --}}
    <div class="bg-blue-800 rounded-2xl p-6 text-white flex items-center gap-6 relative">
        <div class="w-20 h-20 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0 border-4 border-blue-500">
            <span class="text-3xl font-black">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </span>
        </div>
        <div class="flex-1">
            <h2 class="text-2xl font-bold">{{ auth()->user()->name }}</h2>
            <p class="text-blue-200 text-sm mt-1">{{ auth()->user()->email }}</p>
            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold uppercase
                @if(auth()->user()->role == 'admin') bg-red-500
                @elseif(auth()->user()->role == 'manager') bg-purple-500
                @else bg-blue-500 @endif">
                {{ ucfirst(auth()->user()->role) }}
            </span>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-blue-300 text-xs uppercase tracking-wider">Regional</p>
            <p class="text-white font-bold">Jambi</p>
        </div>
    </div>

    <div class="space-y-4">
        <div class="flex justify-between items-center px-2">
            <p class="font-bold text-gray-800 uppercase tracking-wider text-sm">Detail Informasi</p>
            
            {{-- TOMBOL EDIT (Diubah menjadi Toggle) --}}
            @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <button onclick="toggleEdit()" id="btnEdit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
                <span>✎</span> EDIT PROFIL & PASSWORD
            </button>
            @endif
        </div>

        {{-- MODE TAMPILAN DATA (ASLI) --}}
        <div id="viewMode" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                    <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Email</p>
                    <p class="font-semibold text-gray-800">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Role / Jabatan</p>
                    <p class="font-semibold text-gray-800 capitalize">{{ auth()->user()->role }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Bergabung Sejak</p>
                    <p class="font-semibold text-gray-800">{{ auth()->user()->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- MODE EDIT (FORM BARU - HIDDEN BY DEFAULT) --}}
        <div id="editMode" class="hidden space-y-4 animate-in fade-in duration-300">
            {{-- Form Edit Data --}}
            <form action="{{ route('pelanggan.profil.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-blue-100 p-6">
                @csrf
                <p class="text-xs font-bold text-blue-600 uppercase mb-4">Ubah Data Diri</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="border rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nama">
                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="border rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Email">
                </div>
                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold">Simpan Perubahan</button>
            </form>

            {{-- Form Ubah Password --}}
            <form action="{{ route('password.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
                @csrf
                @method('PUT')
                <p class="text-xs font-bold text-red-600 uppercase mb-4">Ganti Password Keamanan</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="password" name="current_password" class="border rounded-xl px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-red-400" placeholder="Password Lama">
                    <input type="password" name="password" class="border rounded-xl px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-red-400" placeholder="Password Baru">
                    <input type="password" name="password_confirmation" class="border rounded-xl px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-red-400" placeholder="Konfirmasi">
                </div>
                <button type="submit" class="mt-4 bg-red-500 text-white px-4 py-2 rounded-lg text-xs font-bold">Update Password</button>
            </form>
        </div>
    </div>

    {{-- STATISTIK TEKNISI (TETAP DIJAGA) --}}
    @if(auth()->user()->role == 'teknisi')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <p class="font-bold text-gray-800 uppercase tracking-wider text-sm">Statistik Pekerjaan</p>
        </div>
        <div class="p-6 grid grid-cols-3 gap-4">
           @php
                $totalTiket  = \App\Models\Ticket::where('technician_id', auth()->id())->count();
                $selesai     = \App\Models\Ticket::where('technician_id', auth()->id())->where('status', 'selesai')->count();
                $aktif       = \App\Models\Ticket::where('technician_id', auth()->id())->whereNotIn('status', ['selesai', 'pending'])->count();
            @endphp
            <div class="bg-blue-50 rounded-xl p-4 text-center">
                <p class="text-3xl font-black text-blue-700">{{ $totalTiket }}</p>
                <p class="text-xs text-blue-500 mt-1">Total Tiket</p>
            </div>
            <div class="bg-green-50 rounded-xl p-4 text-center">
                <p class="text-3xl font-black text-green-700">{{ $selesai }}</p>
                <p class="text-xs text-green-500 mt-1">Selesai</p>
            </div>
            <div class="bg-yellow-50 rounded-xl p-4 text-center">
                <p class="text-3xl font-black text-yellow-700">{{ $aktif }}</p>
                <p class="text-xs text-yellow-500 mt-1">Sedang Aktif</p>
            </div>
        </div>
    </div>
    @endif

    {{-- RINGKASAN SISTEM ADMIN (TETAP DIJAGA) --}}
    @if(in_array(auth()->user()->role, ['admin', 'manager']))
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <p class="font-bold text-gray-800 uppercase tracking-wider text-sm">Ringkasan Sistem</p>
        </div>
        <div class="p-6 grid grid-cols-3 gap-4">
            @php
                $totalLaporan = \App\Models\Ticket::count();
                $totalUser    = \App\Models\User::count();
                $totalTeknisi = \App\Models\User::where('role','teknisi')->count();
            @endphp
            <div class="bg-blue-50 rounded-xl p-4 text-center">
                <p class="text-3xl font-black text-blue-700">{{ $totalLaporan }}</p>
                <p class="text-xs text-blue-500 mt-1">Total Laporan</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4 text-center">
                <p class="text-3xl font-black text-purple-700">{{ $totalUser }}</p>
                <p class="text-xs text-purple-500 mt-1">Total User</p>
            </div>
            <div class="bg-teal-50 rounded-xl p-4 text-center">
                <p class="text-3xl font-black text-teal-700">{{ $totalTeknisi }}</p>
                <p class="text-xs text-teal-500 mt-1">Total Teknisi</p>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- SCRIPT TOGGLE --}}
<script>
    function toggleEdit() {
        const view = document.getElementById('viewMode');
        const edit = document.getElementById('editMode');
        const btn = document.getElementById('btnEdit');

        if (edit.classList.contains('hidden')) {
            edit.classList.remove('hidden');
            view.classList.add('hidden');
            btn.innerHTML = '✕ BATAL';
            btn.classList.replace('bg-blue-600', 'bg-gray-500');
        } else {
            edit.classList.add('hidden');
            view.classList.remove('hidden');
            btn.innerHTML = '✎ EDIT PROFIL & PASSWORD';
            btn.classList.replace('bg-gray-500', 'bg-blue-600');
        }
    }
</script>
@endsection