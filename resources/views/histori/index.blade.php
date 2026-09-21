{{-- ✅ FILE BARU (REVISI): Histori Harian + Timeline --}}
@extends(auth()->user()->role == 'pelanggan' ? 'layouts.app' : 'layouts.admin')
 
@section('title', 'Histori Harian')
@section('page-title', 'Histori & Timeline Gangguan')
@section('page-subtitle', 'Riwayat Penanganan Gangguan per Periode')
 
@section('content')
<div class="space-y-6 @if(auth()->user()->role == 'pelanggan') p-6 max-w-6xl mx-auto @endif">
 
    @if(auth()->user()->role == 'pelanggan')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Histori & Timeline Gangguan</h1>
            <p class="text-xs text-gray-400 uppercase tracking-wider">{{ $labelPeriode }}</p>
        </div>
        <a href="{{ route('pelanggan.dashboard') }}" class="text-sm text-blue-700 font-semibold">← Kembali</a>
    </div>
    @endif
 
    {{-- ================= FILTER PERIODE & DATE RANGE ================= --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('histori.index') }}" class="flex flex-wrap gap-3 items-end">
 
            <div>
                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Periode</label>
                <select name="periode" onchange="this.form.submit()"
                        class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="hari"   {{ $periode == 'hari'   ? 'selected' : '' }}>Hari Ini</option>
                    <option value="minggu" {{ $periode == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan"  {{ $periode == 'bulan'  ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun"  {{ $periode == 'tahun'  ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="semua"  {{ $periode == 'semua'  ? 'selected' : '' }}>Semua Waktu</option>
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
 
            <a href="{{ route('histori.index') }}"
               class="px-5 py-2 rounded-xl text-sm font-bold uppercase tracking-wider text-gray-500 border border-gray-200">
                Reset
            </a>
        </form>
    </div>
 
    {{-- ================= RINGKASAN ANGKA ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Total Tiket</p>
            <p class="text-4xl font-black text-gray-800">{{ str_pad($jumlahTotal, 2, '0', STR_PAD_LEFT) }}</p>
            <p class="text-xs text-gray-400 mt-2">{{ $labelPeriode }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Sudah Selesai</p>
            <p class="text-4xl font-black text-green-600">{{ str_pad($jumlahSelesai, 2, '0', STR_PAD_LEFT) }}</p>
            <p class="text-xs text-green-500 font-semibold mt-2">● Gangguan Teratasi</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Belum Selesai</p>
            <p class="text-4xl font-black text-yellow-500">{{ str_pad($jumlahBelum, 2, '0', STR_PAD_LEFT) }}</p>
            <p class="text-xs text-yellow-500 font-semibold mt-2">● Masih Dikerjakan</p>
        </div>
    </div>
 
    {{-- ================= PENYEBAB GANGGUAN ================= --}}
    @if(! in_array($role, ['pelanggan']))
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <p class="font-bold text-gray-800 text-sm uppercase tracking-wider">Penyebab Gangguan — {{ $labelPeriode }}</p>
        </div>
        <div class="p-5 space-y-3">
            @forelse($penyebab as $nama => $jumlah)
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ $nama }}</p>
                    <div class="w-full bg-gray-100 rounded-full h-2 mt-1">
                        <div class="bg-blue-700 h-2 rounded-full"
                             style="width: {{ $jumlahSelesai > 0 ? round($jumlah / $jumlahSelesai * 100) : 0 }}%"></div>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">{{ $jumlah }} kasus</span>
            </div>
            @empty
            <p class="text-gray-400 text-sm text-center py-3">Belum ada data penyebab gangguan pada periode ini.</p>
            @endforelse
        </div>
    </div>
    @endif
 
    {{-- ================= TABEL PELANGGAN YANG DIKERJAKAN ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <p class="font-bold text-gray-800 text-sm uppercase tracking-wider">Daftar Pelanggan / Tiket — {{ $labelPeriode }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wider bg-gray-50">
                        <th class="px-5 py-3 text-left">ID Tiket</th>
                        <th class="px-5 py-3 text-left">Pelanggan</th>
                        <th class="px-5 py-3 text-left">Teknisi</th>
                        <th class="px-5 py-3 text-left">Penyebab</th>
                        <th class="px-5 py-3 text-left">Tindakan</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Tgl Lapor</th>
                        <th class="px-5 py-3 text-left">Tgl Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 font-bold text-blue-700 text-xs">
                            Fiber To The Home-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}
                        <td class="px-5 py-4 text-gray-800 text-xs font-semibold">{{ $ticket->customer->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-gray-500 text-xs">{{ $ticket->technician->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-gray-600 text-xs">{{ $ticket->penyebab ?? '-' }}</td>
                        <td class="px-5 py-4 text-gray-600 text-xs">{{ $ticket->action_taken ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-bold uppercase text-white {{ $ticket->status_color }}">
                                {{ $ticket->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $ticket->created_at->format('d M Y H:i') }}</td>
                        <td class="px-5 py-4 text-gray-400 text-xs">
                            {{ $ticket->is_selesai ? $ticket->updated_at->format('d M Y H:i') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                            Tidak ada data pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
 
    {{-- ================= TIMELINE AKTIVITAS ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <p class="font-bold text-gray-800 text-sm uppercase tracking-wider">Timeline Aktivitas Penanganan</p>
        </div>
        <div class="p-6 space-y-8">
            @forelse($timeline as $tanggal => $items)
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-800 text-white">
                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $items->count() }} aktivitas</span>
                </div>
 
                <div class="relative border-l-2 border-gray-100 ml-3 space-y-6">
                    @foreach($items as $item)
                    <div class="relative pl-6">
                        <span class="absolute -left-[7px] top-1.5 w-3 h-3 rounded-full
                            {{ in_array($item->status, ['selesai','resolved','normal']) ? 'bg-green-500' : 'bg-yellow-400' }}"></span>
 
                        <p class="text-xs text-gray-400">{{ $item->created_at->format('H:i') }} WIB</p>
                        <p class="text-sm font-semibold text-gray-800">
                            Fiber To The Home-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}
                            {{ $item->ticket->customer->name ?? '-' }} —
                            <span class="text-blue-700">{{ $item->status_label }}</span>
                        </p>
                        <p class="text-xs text-gray-500">{{ $item->notes }}</p>
                        <p class="text-xs text-gray-400 italic">Oleh: {{ $item->user->name ?? 'Sistem' }}</p>
 
                        @if($item->photo_path)
                        <img src="{{ asset('storage/'.$item->photo_path) }}"
                             class="mt-2 rounded-xl max-h-28 object-cover border border-gray-100">
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm text-center py-6">Belum ada aktivitas pada periode ini.</p>
            @endforelse
        </div>
    </div>
 
</div>
@endsection