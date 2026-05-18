@extends('layouts.admin')

@section('title', 'Dashboard Teknisi')
@section('page-title', 'Dashboard Teknisi')
@section('page-subtitle', 'Daftar Tiket Yang Ditugaskan')

@section('content')
<div class="space-y-4">

    @if($tickets->isEmpty())
    <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
        <p class="text-4xl mb-3">📋</p>
        <p class="text-gray-500">Belum ada tiket yang ditugaskan kepada Anda.</p>
    </div>
    @else
        @foreach($tickets as $ticket)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <span class="font-bold text-blue-700">#FTTH-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</span>
                    <span class="text-gray-400 mx-2">•</span>
                    <span class="text-gray-600 text-sm">{{ $ticket->customer->name ?? 'N/A' }}</span>
                    <span class="text-gray-400 mx-2">•</span>
                    <span class="text-gray-400 text-xs">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                </div>
                {{-- Menggunakan getStatusColorAttribute dan getStatusLabelAttribute dari Model --}}
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase text-white {{ $ticket->status_color }}">
                    {{ $ticket->status_label }}
                </span>
            </div>

            <div class="p-6 grid grid-cols-2 gap-6">
                
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-400 uppercase">ID PLN Pelanggan</p>
                        <p class="font-semibold text-gray-800">{{ $ticket->customer_id_pln ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Alamat</p>
                        <p class="font-semibold text-gray-800">{{ $ticket->alamat_pelanggan }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Deskripsi Gangguan</p>
                        <p class="text-gray-700">{{ $ticket->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    @if($ticket->foto_kondisi)
                    <div>
                        <p class="text-xs text-gray-400 uppercase mb-2">Foto dari Pelanggan</p>
                        <img src="{{ asset('storage/'.$ticket->foto_kondisi) }}" 
                             class="rounded-xl max-h-32 object-cover">
                    </div>
                    @endif

                    {{-- Menampilkan Penyebab & Action jika sudah selesai --}}
                    @if($ticket->status == 'selesai') {{-- REVISI: Ganti resolved ke selesai --}}
                    <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-100">
                        <p class="text-xs text-green-700 font-bold uppercase mb-1">Laporan Akhir</p>
                        <p class="text-xs text-gray-700"><strong>Penyebab:</strong> {{ $ticket->penyebab }}</p>
                        <p class="text-xs text-gray-700"><strong>Tindakan:</strong> {{ $ticket->action_taken }}</p>
                    </div>
                    @endif

                    @if($ticket->updates && $ticket->updates->count() > 0)
                    <div class="mt-4">
                        <p class="text-xs text-gray-400 uppercase mb-3">Timeline Perkembangan</p>
                        <div class="space-y-2">
                            @foreach($ticket->updates->sortByDesc('created_at') as $update)
                            <div class="flex gap-3 items-start">
                                <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0 bg-blue-500">
                                </div>
                                <div>
                                    {{-- Menggunakan status_label agar teks timeline rapi di teknisi --}}
                                    <p class="text-sm font-semibold text-gray-700">{{ $update->status_label }}</p>
                                    @if($update->notes)
                                        <p class="text-xs text-gray-500">{{ $update->notes }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">{{ $update->created_at->format('d M H:i') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                @if($ticket->status != 'selesai') {{-- REVISI: Ganti resolved ke selesai --}}
                <div class="bg-gray-50 rounded-xl p-5">
                    <p class="font-bold text-gray-700 mb-4 uppercase text-xs tracking-wider">Update Status Pekerjaan</p>
                    {{-- Nama Route disesuaikan dengan TeknisiController --}}
                    <form action="{{ route('teknisi.update', $ticket->id) }}" 
                          method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Status</label>
                            <select name="status" id="status-{{ $ticket->id }}" 
                                    onchange="toggleResolvedFields({{ $ticket->id }})"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                {{-- REVISI: Value disesuaikan dengan ENUM Database --}}
                                <option value="perjalanan" {{ $ticket->status == 'perjalanan' ? 'selected' : '' }}>🚗 Menuju Lokasi</option>
                                <option value="lokasi" {{ $ticket->status == 'lokasi' ? 'selected' : '' }}>📍 Tiba di Lokasi</option>
                                <option value="perbaikan" {{ $ticket->status == 'perbaikan' ? 'selected' : '' }}>🔧 Proses Perbaikan</option>
                                <option value="selesai" {{ $ticket->status == 'selesai' ? 'selected' : '' }}>✅ Jaringan Normal</option>
                            </select>
                        </div>

                        {{-- Bagian ini muncul hanya jika status selesai dipilih --}}
                        <div id="resolved-fields-{{ $ticket->id }}" class="hidden space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block text-blue-700">Penyebab Gangguan (Wajib)</label>
                                <textarea name="penyebab" rows="2" 
                                          placeholder="Contoh: Kabel FO Putus..."
                                          class="w-full border border-blue-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block text-blue-700">Tindakan/Action (Wajib)</label>
                                <textarea name="action_taken" rows="2" 
                                          placeholder="Contoh: Penyambungan kabel (Splicing)..."
                                          class="w-full border border-blue-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" rows="2" 
                                      placeholder="Tambahkan info jika diperlukan..."
                                      class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Foto Bukti (opsional)</label>
                            <input type="file" name="photo" accept="image/*"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-white">
                        </div>
                        <button type="submit"
                                class="w-full bg-blue-800 hover:bg-blue-900 text-white py-3 rounded-xl text-sm font-bold uppercase tracking-wider transition">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
                @else
                <div class="bg-green-50 rounded-xl p-5 flex flex-col items-center justify-center text-center">
                    <p class="text-4xl mb-2">✅</p>
                    <p class="font-bold text-green-700">Jaringan Normal</p>
                    <p class="text-green-600 text-sm mt-1">Pekerjaan selesai</p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    @endif
</div>

<script>
function toggleResolvedFields(ticketId) {
    const status = document.getElementById('status-' + ticketId).value;
    const fields = document.getElementById('resolved-fields-' + ticketId);
    
    // Pastikan ini 'selesai' (huruf kecil)
    if (status === 'selesai') {
        fields.classList.remove('hidden');
    } else {
        fields.classList.add('hidden');
    }
}
</script>
@endsection