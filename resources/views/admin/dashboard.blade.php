@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Ringkasan Strategis')
@section('page-subtitle', 'Monitoring Gangguan Real-Time')

@section('content')
<div class="space-y-6">

    <div id="notif-panel" class="hidden bg-white rounded-2xl shadow-lg border border-red-100 p-5">
        <p class="font-bold text-gray-800 text-sm mb-3 uppercase tracking-wider">🔔 Gangguan Menunggu Tindakan</p>
        @forelse($notifications as $notif)
        <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
            <div class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800">#FTTH-{{ str_pad($notif->id,3,'0',STR_PAD_LEFT) }} — {{ $notif->customer->name }}</p>
                <p class="text-xs text-gray-400">{{ Str::limit($notif->description, 40) }} · {{ $notif->created_at->diffForHumans() }}</p>
            </div>
            <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">Menunggu</span>
        </div>
        @empty
        <p class="text-gray-400 text-sm text-center py-2">✅ Tidak ada gangguan menunggu.</p>
        @endforelse
    </div>

    <div class="grid grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-red-50 rounded-bl-full"></div>
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-3">Laporan Masuk</p>
            <p class="text-5xl font-black text-gray-800">{{ str_pad($totalPending, 2, '0', STR_PAD_LEFT) }}</p>
            <p class="text-xs text-red-500 font-semibold mt-3">● Menunggu Tindakan</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-yellow-50 rounded-bl-full"></div>
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-3">Dalam Proses</p>
            <p class="text-5xl font-black text-gray-800">{{ str_pad($totalProses, 2, '0', STR_PAD_LEFT) }}</p>
            <p class="text-xs text-yellow-500 font-semibold mt-3">● Sedang Ditangani</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-green-50 rounded-bl-full"></div>
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-3">Laporan Selesai</p>
            <p class="text-5xl font-black text-gray-800">{{ $totalSelesai }}</p>
            <p class="text-xs text-green-500 font-semibold mt-3">● Bulan Ini</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-blue-50 rounded-bl-full"></div>
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-3">Total Teknisi</p>
            <p class="text-5xl font-black text-gray-800">{{ str_pad($totalTeknisi, 2, '0', STR_PAD_LEFT) }}</p>
            <p class="text-xs text-blue-500 font-semibold mt-3">● Aktif Bertugas</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">

        <div class="col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 flex justify-between items-center border-b border-gray-100">
                <p class="font-bold text-gray-800 uppercase tracking-wider text-sm">Log Gangguan Terkini</p>
                
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wider bg-gray-50">
                        <th class="px-5 py-3 text-left">ID Tiket</th>
                        <th class="px-5 py-3 text-left">Pelanggan</th>
                        <th class="px-5 py-3 text-left">Masalah</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 font-bold text-blue-700 text-xs">
                            #FTTH-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-medium text-gray-800 text-xs">{{ $ticket->customer->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $ticket->customer_id_pln }}</p>
                        </td>
                        <td class="px-5 py-4 text-gray-600 text-xs">{{ Str::limit($ticket->description, 25) }}</td>
                        <td class="px-5 py-4">
                            @if($ticket->status == 'selesai')
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600 uppercase">✅ Selesai</span>
                            @elseif($ticket->status == 'pending')
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600 uppercase">🔴 Menunggu</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-600 uppercase">🟡 Proses</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if($ticket->status == 'pending')
                            <form action="{{ route('admin.tickets.assign', $ticket->id) }}" method="POST" class="flex gap-1 items-center technician-form">
                                @csrf
                                <select name="technician_id" class="technician-select border border-gray-200 rounded-lg px-2 py-1 text-xs bg-gray-50 max-w-24 outline-none focus:ring-1 focus:ring-blue-500 transition">
                                    <option value="">Pilih Teknisi</option>
                                    @foreach(\App\Models\User::where('role','teknisi')->get() as $tek)
                                        <option value="{{ $tek->id }}">{{ $tek->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" disabled class="btn-tugaskan bg-gray-400 text-white px-2 py-1 rounded-lg text-xs font-bold uppercase whitespace-nowrap cursor-not-allowed transition duration-200">
                                    Tugaskan
                                </button>
                            </form>
                            @else
                                <span class="text-gray-400 text-xs">{{ $ticket->technician?->name ?? 'Archive' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-400">Belum ada laporan masuk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="space-y-5">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <p class="font-bold text-gray-800 text-sm uppercase tracking-wider">Daftar Teknisi</p>
                </div>
                <div class="p-4 space-y-3">
                    @foreach(\App\Models\User::where('role','teknisi')->get() as $tek)
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-700 font-bold text-xs">{{ strtoupper(substr($tek->name, 0, 2)) }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $tek->name }}</p>
                            @php $aktif = \App\Models\Ticket::where('technician_id', $tek->id)->whereNotIn('status',['selesai','pending'])->count(); @endphp
                            <p class="text-xs text-gray-400">{{ $aktif }} tiket aktif</p>
                        </div>
                        <span class="w-2 h-2 rounded-full {{ $aktif > 0 ? 'bg-yellow-400' : 'bg-green-400' }}"></span>
                    </div>
                    @endforeach
                    @if(\App\Models\User::where('role','teknisi')->count() == 0)
                        <p class="text-gray-400 text-xs text-center py-2">Belum ada teknisi</p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <p class="font-bold text-gray-800 text-sm uppercase tracking-wider">Aktivitas Terbaru</p>
                </div>
                <div class="p-4 space-y-3">
                    @foreach($tickets->take(5) as $ticket)
                    <div class="flex gap-3 items-start">
                        <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0
    {{ $ticket->status == 'selesai' ? 'bg-green-400' : ($ticket->status == 'pending' ? 'bg-red-400' : 'bg-yellow-400') }}">
</div>
                        <div>
                            <p class="text-xs font-medium text-gray-700">
                                #FTTH-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }} · {{ $ticket->customer->name }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $ticket->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                    @if($tickets->isEmpty())
                        <p class="text-gray-400 text-xs text-center py-2">Belum ada aktivitas</p>
                    @endif
                </div>
            </div>

            <div class="bg-blue-800 rounded-2xl p-5 text-white">
                <p class="font-bold text-sm mb-3 uppercase tracking-wider">Aksi Cepat</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.create') }}" 
                       class="flex items-center gap-2 bg-blue-700 hover:bg-blue-600 px-4 py-2.5 rounded-xl text-sm transition">
                        <span>➕</span> Tambah User Baru
                    </a>
                    <a href="{{ route('admin.laporan') }}" 
                       class="flex items-center gap-2 bg-blue-700 hover:bg-blue-600 px-4 py-2.5 rounded-xl text-sm transition">
                        <span>📥</span> Export Laporan
                    </a>
                    <a href="{{ route('admin.users') }}" 
                       class="flex items-center gap-2 bg-blue-700 hover:bg-blue-600 px-4 py-2.5 rounded-xl text-sm transition">
                        <span>👥</span> Kelola User
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ TAMBAHAN: Script untuk tombol lonceng & Interaksi Tombol Tugaskan --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Logika Lonceng Notifikasi
    const btn = document.getElementById('notif-btn');
    const panel = document.getElementById('notif-panel');
    if (btn && panel) {
        btn.addEventListener('click', function() {
            panel.classList.toggle('hidden');
            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    }

    // 2. Logika Tombol Tugaskan (Grey ke Biru)
    const selects = document.querySelectorAll('.technician-select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            const form = this.closest('.technician-form');
            const button = form.querySelector('.btn-tugaskan');
            
            if (this.value !== "") {
                // Aktifkan & Ubah ke Biru
                button.disabled = false;
                button.classList.remove('bg-gray-400', 'cursor-not-allowed');
                button.classList.add('bg-blue-800', 'hover:bg-blue-900', 'shadow-md');
            } else {
                // Matikan & Ubah ke Grey
                button.disabled = true;
                button.classList.add('bg-gray-400', 'cursor-not-allowed');
                button.classList.remove('bg-blue-800', 'hover:bg-blue-900', 'shadow-md');
            }
        });
    });
});
</script>
@endsection