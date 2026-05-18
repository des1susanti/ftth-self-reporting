<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // Tambahkan ini

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login (ini kode yang Anda buat tadi).
     */
  public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

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

    return redirect('/');
}

    /**
     * Menangani Logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}