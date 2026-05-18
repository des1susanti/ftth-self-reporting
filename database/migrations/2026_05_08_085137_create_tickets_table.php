<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            // Relasi ke user (pelanggan yang melapor)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke teknisi (bisa kosong/null sebelum admin menugaskan)
            $table->foreignId('teknisi_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Data Gangguan
            $table->string('nomor_tiket')->unique(); // Contoh: TKT-20260508-001
            $table->text('alamat_pelanggan');
            $table->string('foto_kondisi')->nullable(); // Untuk menyimpan path gambar
            
            // Monitoring Progres (Enum sesuai permintaan Anda)
            $table->enum('status', [
                'pending',      // Laporan baru masuk
                'perjalanan',   // Teknisi menuju lokasi
                'lokasi',       // Teknisi sampai di lokasi
                'perbaikan',    // Sedang dikerjakan
                'selesai'       // Jaringan normal kembali
            ])->default('pending');
            
            // Detail Teknis
            $table->text('penyebab_gangguan')->nullable();
            $table->text('action_tim')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};