@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#F4F7FB] py-8 px-4 lg:px-8">

    <div class="max-w-3xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-6">

            <h1 class="text-2xl lg:text-3xl font-semibold text-[#005494]">
                Buat Laporan Gangguan
            </h1>

            <p class="text-sm text-gray-500 mt-2">
                Isi form berikut untuk melaporkan gangguan internet
            </p>
        </div>


        {{-- CARD --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 lg:p-8">

            <form action="{{ route('pelanggan.laporan.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-6">

                @csrf


                {{-- ALAMAT --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Alamat Lengkap
                    </label>

                    <textarea
                        name="alamat_pelanggan"
                        rows="4"
                        placeholder="Masukkan alamat lengkap lokasi gangguan..."
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#005494] focus:border-transparent resize-none">{{ old('alamat_pelanggan') }}</textarea>

                    @error('alamat_pelanggan')

                        <p class="text-red-500 text-xs mt-2">
                            {{ $message }}
                        </p>

                    @enderror
                </div>


                {{-- JUDUL GANGGUAN --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Judul Gangguan
                    </label>

                    <input
                        type="text"
                        name="judul_gangguan"
                        value="{{ old('judul_gangguan') }}"
                        placeholder="Contoh: Internet mati total"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#005494] focus:border-transparent">

                    @error('judul_gangguan')

                        <p class="text-red-500 text-xs mt-2">
                            {{ $message }}
                        </p>

                    @enderror
                </div>


                {{-- DESKRIPSI --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi Keluhan
                    </label>

                    <textarea
                        name="deskripsi_keluhan"
                        rows="5"
                        placeholder="Jelaskan kendala yang sedang dialami..."
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#005494] focus:border-transparent resize-none">{{ old('deskripsi_keluhan') }}</textarea>

                    @error('deskripsi_keluhan')

                        <p class="text-red-500 text-xs mt-2">
                            {{ $message }}
                        </p>

                    @enderror
                </div>


                {{-- FOTO --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Foto Kondisi Perangkat
                    </label>

                    <div class="border-2 border-dashed border-cyan-200 rounded-2xl p-6 bg-[#F9FCFF]">

                        <input
                            type="file"
                            name="foto_kondisi"
                            accept="image/*"
                            class="w-full text-sm text-gray-500
                                   file:mr-4
                                   file:py-2
                                   file:px-4
                                   file:rounded-xl
                                   file:border-0
                                   file:text-sm
                                   file:font-medium
                                   file:bg-[#005494]
                                   file:text-white
                                   hover:file:bg-[#00457A]">

                        <p class="text-xs text-gray-400 mt-3">
                            Format JPG / PNG • Maksimal 2MB
                        </p>
                    </div>

                    @error('foto_kondisi')

                        <p class="text-red-500 text-xs mt-2">
                            {{ $message }}
                        </p>

                    @enderror
                </div>


                {{-- BUTTON --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-2">

                    <button type="submit"
                            class="bg-[#005494] hover:bg-[#00457A] text-white px-6 py-3 rounded-2xl text-sm font-semibold transition shadow-sm">

                        Kirim Laporan
                    </button>

                    <a href="{{ route('pelanggan.dashboard') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-2xl text-sm font-medium transition text-center">

                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection