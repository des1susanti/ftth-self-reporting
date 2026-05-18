@extends('layouts.admin')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil & Keamanan')
@section('page-subtitle', 'Perbarui informasi akun dan password Anda')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('pelanggan.profil.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <p class="font-bold text-gray-800 uppercase tracking-wider text-sm">Informasi Dasar</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block uppercase">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" 
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block uppercase">Alamat Email</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" 
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <p class="font-bold text-gray-800 uppercase tracking-wider text-sm">Ganti Password (Opsional)</p>
            </div>
            <div class="p-6">
                <p class="text-xs text-gray-500 mb-4">*Kosongkan jika tidak ingin mengubah password</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block uppercase">Password Baru</label>
                        <input type="password" name="password" placeholder="Minimal 6 karakter"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block uppercase">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl text-sm font-bold uppercase transition shadow-lg shadow-blue-200">
                Simpan Semua Perubahan
            </button>
            <a href="{{ route('pelanggan.profil') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-8 py-3 rounded-xl text-sm font-bold uppercase transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection