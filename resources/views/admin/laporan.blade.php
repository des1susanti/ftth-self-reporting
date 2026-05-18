@extends('layouts.admin')

@section('title', 'Data Gangguan')
@section('page-title', 'Data Laporan Gangguan')
@section('page-subtitle', 'Audit Operasional & Rekapitulasi')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('admin.laporan') }}" class="flex gap-3 items-center">
            <select name="periode" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Waktu</option>
                <option value="minggu" {{ request('periode') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="bulan" {{ request('periode') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="tahun" {{ request('periode') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
            </select>

            {{-- ✅ REVISI: Opsi status disesuaikan dengan ENUM di Database (DBeaver) agar filter akurat --}}
            <select name="status" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="pending"    {{ request('status') == 'pending'    ? 'selected' : '' }}>🔴 Menunggu</option>
                <option value="assigned"   {{ request('status') == 'assigned'   ? 'selected' : '' }}>🔵 Ditugaskan</option>
                <option value="perjalanan" {{ request('status') == 'perjalanan' ? 'selected' : '' }}>🚗 Perjalanan</option>
                <option value="perbaikan"  {{ request('status') == 'perbaikan'  ? 'selected' : '' }}>🔧 Perbaikan</option>
                <option value="selesai"    {{ request('status') == 'selesai'    ? 'selected' : '' }}>✅ Selesai</option>
            </select>

            <div class="ml-auto flex gap-2">
                <a href="{{ route('admin.export.excel') }}" 
                   class="bg-teal-500 hover:bg-teal-600 text-white px-5 py-2 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center gap-2">
                    <span>📊</span> Excel
                </a>

                <a href="{{ route('admin.export.pdf') }}" 
                   class="bg-red-500 hover:bg-red-700 text-white px-5 py-2 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center gap-2">
                    <span>📄</span> PDF
                </a>
            </div>

        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 text-left">ID Tiket</th>
                    <th class="px-6 py-4 text-left">Pelanggan</th>
                    <th class="px-6 py-4 text-left">Judul Masalah</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-left">Teknisi</th>
                    <th class="px-6 py-4 text-left">Tanggal</th>
                    <th class="px-6 py-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-blue-700">
                        #FTTH-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $ticket->customer->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ Str::limit($ticket->description, 35) }}</td>
                    <td class="px-6 py-4">
                        {{-- ✅ REVISI: Menggunakan Accessor dari Model agar warna & label konsisten dengan Teknisi --}}
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase text-white {{ $ticket->status_color }}">
                            {{ $ticket->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $ticket->technician?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $ticket->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        {{-- Tombol Detail/Aksi --}}
                        <div class="flex items-center gap-2">
                            @if($ticket->status == 'pending')
                                <span class="bg-blue-800 text-white px-3 py-1.5 rounded-lg text-xs font-bold uppercase">Proses</span>
                            @else
                                <span class="text-gray-400 text-xs italic">Archived</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">Tidak ada data gangguan ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection