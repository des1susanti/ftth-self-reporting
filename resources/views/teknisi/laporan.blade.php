@extends('layouts.admin')
 
@section('title', 'Data Gangguan')
@section('page-title', 'Riwayat Pekerjaan Saya')
@section('page-subtitle', 'History Tiket & Gangguan')
 
@section('content')
<div class="space-y-6">
 
    <!-- Filter -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        {{-- ✅ REVISI: filter periode dilengkapi "Hari Ini", rentang tanggal, dan status --}}
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Periode</label>
                <select name="periode" onchange="this.form.submit()"
                        class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Waktu</option>
                    <option value="hari"   {{ request('periode') == 'hari'   ? 'selected' : '' }}>Hari Ini</option>
                    <option value="minggu" {{ request('periode') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan"  {{ request('periode') == 'bulan'  ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun"  {{ request('periode') == 'tahun'  ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>
 
            <div>
                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
 
            <div>
                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
 
            <div>
                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Status</label>
                <select name="status" onchange="this.form.submit()"
                        class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>✅ Sudah Selesai</option>
                    <option value="belum"   {{ request('status') == 'belum'   ? 'selected' : '' }}>🟡 Belum Selesai</option>
                </select>
            </div>
 
            <button type="submit"
                    class="bg-blue-800 hover:bg-blue-900 text-white px-5 py-2 rounded-xl text-sm font-bold uppercase tracking-wider">
                Tampilkan
            </button>
 
            <a href="{{ route('histori.index', ['periode' => 'hari']) }}"
               class="px-5 py-2 rounded-xl text-sm font-bold uppercase tracking-wider text-blue-700 border border-blue-200">
                Timeline
            </a>
        </form>
    </div>
 
    {{-- ✅ REVISI BARU: ringkasan jumlah pekerjaan sesuai filter --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total Pelanggan Dikerjakan</p>
            <p class="text-3xl font-black text-gray-800">{{ str_pad($tickets->count(), 2, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Sudah Selesai</p>
            <p class="text-3xl font-black text-green-600">{{ str_pad($jumlahSelesai ?? 0, 2, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Belum Selesai</p>
            <p class="text-3xl font-black text-yellow-500">{{ str_pad($jumlahBelum ?? 0, 2, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>
 
    <!-- Tabel -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 text-left">ID Tiket</th>
                    <th class="px-6 py-4 text-left">Pelanggan</th>
                    <th class="px-6 py-4 text-left">Masalah</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-blue-700">
                        Fiber To The Home-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $ticket->customer->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ Str::limit($ticket->description, 40) }}</td>
                    <td class="px-6 py-4">
                        @if($ticket->status == 'resolved')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600 uppercase">Selesai</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-600 uppercase">{{ $ticket->status_label }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $ticket->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">Belum ada riwayat pekerjaan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection