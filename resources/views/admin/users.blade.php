@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen Pengguna')
{{-- Gunakan kurung kurawal ganda seperti di bawah ini --}}
@section('page-subtitle', 'Total: ' . $users->count() . ' Pengguna Terdaftar')

@section('content')
<div class="space-y-6">

    <!-- Search & Tambah -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('admin.users') }}" class="flex gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari Nama atau ID Pelanggan..."
                   class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="role" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Role</option>
                <option value="pelanggan" {{ request('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                <option value="teknisi"   {{ request('role') == 'teknisi'   ? 'selected' : '' }}>Teknisi</option>
                <option value="admin"     {{ request('role') == 'admin'     ? 'selected' : '' }}>Admin</option>
                <option value="manager"   {{ request('role') == 'manager'   ? 'selected' : '' }}>Manager</option>
            </select>
            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-medium">
                Cari
            </button>
            <a href="{{ route('admin.users.create') }}"
               class="bg-blue-800 hover:bg-blue-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider ml-auto whitespace-nowrap">
                + Tambah User Baru
            </a>
        </form>
    </div>

    <!-- Tabel User -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 text-left">User</th>
                    <th class="px-6 py-4 text-left">Username / ID</th>
                    <th class="px-6 py-4 text-left">Role</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-left">Kelola</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-700 font-bold text-xs">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            </div>
                            <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4">
    <span class="px-3 py-1 rounded-full text-xs font-medium border
        @if($user->role == 'admin') border-red-200 text-red-600 bg-red-50
        @elseif($user->role == 'manager') border-purple-200 text-purple-600 bg-purple-50
        @elseif($user->role == 'teknisi') border-blue-200 text-blue-600 bg-blue-50
        @else border-gray-200 text-gray-600 bg-gray-50 @endif">
        {{ ucfirst($user->role ?? '-') }}
    </span>
</td>
<td class="px-6 py-4">
    <span class="text-green-500 font-bold text-xs uppercase">● Aktif</span>
</td>
<td class="px-6 py-4">
    <div class="flex items-center gap-3">
        <span class="text-gray-400 text-xs">Kelola</span>
        @if(auth()->user()->id !== $user->id)
        <form action="{{ route('admin.users.delete', $user->id) }}" 
              method="POST"
              onsubmit="return confirm('Yakin hapus user {{ $user->name }}?')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="text-red-500 hover:text-red-700 text-xs font-semibold hover:underline">
                Hapus
            </button>
        </form>
        @endif
    </div>
</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">Tidak ada user ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection