<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('tickets', function (Blueprint $table) {
        // Tulis kode ini di dalam sini:
        $table->string('progress_status')->default('assigned'); 
        $table->text('penyebab')->nullable();
        $table->text('action_taken')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('tickets', function (Blueprint $table) {
        $table->dropColumn(['progress_status', 'penyebab', 'action_taken']);
    });
}
        
    
};
