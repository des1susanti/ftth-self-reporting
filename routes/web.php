<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (auth()->check()) {

        $role = auth()->user()->role;

        if ($role == 'admin' || $role == 'manager') {
            return redirect()->route('admin.dashboard');
        }

        if ($role == 'teknisi') {
            return redirect()->route('teknisi.dashboard');
        }

        if ($role == 'pelanggan') {
            return redirect()->route('pelanggan.dashboard');
        }
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
/*
|--------------------------------------------------------------------------
| PELANGGAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

        Route::get('/dashboard', [PelangganController::class, 'dashboard'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | LAPORAN & TRACKING
        |--------------------------------------------------------------------------
        */
        Route::get('/laporan/buat', [TicketController::class, 'create'])
            ->name('laporan.create');

        Route::post('/laporan/store', [TicketController::class, 'store'])
            ->name('laporan.store');

        Route::get('/laporan/{ticket}/track', [TicketController::class, 'track'])
            ->name('laporan.track');

    }); 
/*
|--------------------------------------------------------------------------
| PROFILE & PASSWORD SYSTEM (CLEAN VERSION)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // --- 1. ROUTE UTAMA PROFIL (Bisa diakses semua role) ---
    Route::get('/profil', function() {
        $role = strtolower(auth()->user()->role); // Paksa huruf kecil agar aman
        
        // Jika Admin, Manager, atau Teknisi: Buka halaman profil dashboard
        if (in_array($role, ['admin', 'manager', 'teknisi'])) {
            return view('profile.profil'); 
        }
        
        // Jika Pelanggan: Gunakan fungsi edit dari ProfileController (Sesuai kode lama Anda)
        return app(\App\Http\Controllers\ProfileController::class)->edit(request());
    })->name('pelanggan.profil');


    // --- 2. ROUTE EDIT KHUSUS (Hanya Admin & Manager) ---
    // Ini rute yang dipanggil tombol "Edit Profil" Anda tadi
    Route::get('/edit-profil-admin', function () {
        $role = strtolower(auth()->user()->role);
        
        if (in_array($role, ['admin', 'manager'])) {
            return view('profile.edit'); // Membuka file edit.blade.php (yang ada sidebar biru)
        }
        
        // Jika bukan admin/manager tapi maksa akses, lempar balik ke profil utama
        return redirect()->route('pelanggan.profil')->with('error', 'Akses ditolak.');
    })->name('profil.edit.admin');


    // --- 3. PROSES UPDATE & PASSWORD (Tetap dipertahankan) ---
    Route::post('/profil/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('pelanggan.profil.update');
    Route::get('/ubah-password', [App\Http\Controllers\ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('/ubah-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');

});
/*
|--------------------------------------------------------------------------
| ADMIN & MANAGER
|--------------------------------------------------------------------------
*/

// Menggunakan middleware 'role' yang baru kita daftarkan di bootstrap/app.php
Route::middleware(['auth', 'role:admin,manager'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/tickets/{ticket}/assign', [AdminController::class, 'assign'])
            ->name('tickets.assign');

        Route::get('/users', [AdminController::class, 'users'])
            ->name('users');

        Route::get('/users/create', [AdminController::class, 'createUser'])
            ->name('users.create');

        Route::post('/users/store', [AdminController::class, 'storeUser'])
            ->name('users.store');

        Route::get('/laporan', [AdminController::class, 'laporan'])
            ->name('laporan');

        Route::get('/export-excel', [App\Http\Controllers\ExportController::class, 'exportExcel'])
            ->name('export.excel');

        Route::get('/export-pdf', [App\Http\Controllers\ExportController::class, 'exportPdf'])
            ->name('export.pdf');

        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])
            ->name('users.delete');
    });


/*
|--------------------------------------------------------------------------
| TEKNISI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('teknisi')
    ->name('teknisi.')
    ->group(function () {

        Route::get('/dashboard', [TeknisiController::class, 'dashboard'])
            ->name('dashboard');

        // REVISI: Ubah 'tickets.update' menjadi 'update' agar sesuai dengan route('teknisi.update') di Blade
        // Dan ubah POST menjadi PATCH agar sesuai dengan @method('PATCH') di form Blade Anda
        Route::patch('/update/{ticket}', [TeknisiController::class, 'update'])
            ->name('update'); 

        Route::get('/laporan', [TeknisiController::class, 'laporan'])
            ->name('laporan');
    });