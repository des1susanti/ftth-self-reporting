<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengecekan agar tidak error jika kolom sudah terlanjur ada
        if (!Schema::hasColumn('tickets', 'technician_id')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->unsignedBigInteger('technician_id')->nullable()->after('customer_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('technician_id');
        });
    }
};