@extends('layouts.admin')

@section('title', 'Data Gangguan')
@section('page-title', 'Riwayat Pekerjaan Saya')
@section('page-subtitle', 'History Tiket & Gangguan')

@section('content')
<div class="space-y-6">

    <!-- Filter -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <form method="GET" class="flex gap-3">
            <select name="periode" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Waktu</option>
                <option value="minggu" {{ request('periode') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="bulan"  {{ request('periode') == 'bulan'  ? 'selected' : '' }}>Bulan Ini</option>
                <option value="tahun"  {{ request('periode') == 'tahun'  ? 'selected' : '' }}>Tahun Ini</option>
            </select>
        </form>
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
                        #FTTH-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}
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