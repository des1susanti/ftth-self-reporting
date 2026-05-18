@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">

    <div class="mb-6">
        <a href="{{ route('pelanggan.dashboard') }}" class="text-blue-600 hover:underline text-sm">← Kembali ke Dashboard</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Tracking Laporan: {{ $ticket->nomor_tiket }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-5 mb-6 border border-gray-100">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">ID Pelanggan PLN</p>
                <p class="font-bold text-gray-800">{{ $ticket->customer_id_pln }}</p>
            </div>
            <div>
                <p class="text-gray-500">Status Terakhir</p>
                <span class="px-3 py-0.5 rounded-full text-xs font-bold text-white {{ $ticket->status_color }}">
                    {{ $ticket->status_label }}
                </span>
            </div>
            <div class="col-span-2">
                <p class="text-gray-500">Alamat Pelanggan</p>
                <p class="font-semibold">{{ $ticket->alamat_pelanggan }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal Laporan</p>
                <p class="font-semibold">{{ $ticket->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div>
                <p class="text-gray-500">Teknisi Bertugas</p>
                <p class="font-semibold text-blue-600">{{ $ticket->technician->name ?? 'Belum Ditugaskan' }}</p>
            </div>
            <div class="col-span-2 border-t pt-3 mt-2">
                <p class="text-gray-500">Deskripsi Gangguan</p>
                <p class="font-medium text-gray-700 italic">"{{ $ticket->description }}"</p>
            </div>
            @if($ticket->foto_kondisi)
            <div class="col-span-2">
                <p class="text-gray-500 mb-2 font-bold">Foto Laporan Awal:</p>
                <img src="{{ asset('storage/' . $ticket->foto_kondisi) }}" 
                     class="rounded-lg max-h-48 w-full object-cover border shadow-sm">
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-5 border border-gray-100">
        <h2 class="font-bold text-gray-800 mb-6 flex items-center">
            <span class="mr-2 text-blue-500">📍</span> Timeline Perkembangan
        </h2>

        @if($ticket->updates->isEmpty())
            <div class="text-center py-10">
                <p class="text-gray-400 text-sm">Belum ada update dari teknisi di lapangan.</p>
            </div>
        @else
            <div class="space-y-0">
                {{-- REVISI: Menggunakan sortByDesc agar status terbaru muncul paling atas --}}
                @foreach($ticket->updates->sortByDesc('created_at') as $update)
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full border-4 border-white shadow-sm mt-1
                            @if($update->status == 'selesai') bg-green-500
                            @else bg-blue-500 @endif">
                        </div>
                        @if(!$loop->last)
                            <div class="w-0.5 bg-gray-100 flex-1 my-1"></div>
                        @endif
                    </div>
                    <div class="pb-8">
                        <p class="font-bold text-gray-900 uppercase text-sm tracking-wide">
                            {{ $update->status }}
                        </p>
                        
                        @if($update->notes)
                            <p class="text-gray-600 text-sm mt-1 bg-gray-50 p-2 rounded-md border border-gray-100 italic">
                                "{{ $update->notes }}"
                            </p>
                        @endif

                        {{-- TAMBAHAN: Munculkan Penyebab & Tindakan jika status Selesai --}}
                        @if($update->status == 'selesai')
                            <div class="mt-3 p-3 bg-green-50 border border-green-100 rounded-xl">
                                <p class="text-[10px] font-bold text-green-700 uppercase mb-2">Laporan Hasil Perbaikan:</p>
                                <div class="space-y-1">
                                    <p class="text-xs text-gray-700"><strong>Penyebab:</strong> {{ $ticket->penyebab ?? 'Terlampir di catatan' }}</p>
                                    <p class="text-xs text-gray-700"><strong>Tindakan:</strong> {{ $ticket->action_taken ?? 'Sudah ditangani teknisi' }}</p>
                                </div>
                            </div>
                        @endif

                        @if($update->photo_path)
                            <a href="{{ asset('storage/' . $update->photo_path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $update->photo_path) }}" 
                                     class="rounded-xl max-h-40 object-cover mt-3 border shadow-sm hover:opacity-90 transition">
                            </a>
                        @endif

                        <div class="flex items-center gap-2 mt-2 text-gray-400 text-[10px]">
                            <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $update->created_at->format('d M, H:i') }} WIB</span>
                            <span>•</span>
                            <span>Oleh: {{ $update->user->name ?? 'Teknisi' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection