<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Menampilkan halaman dashboard/riwayat pelanggan
     */
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->latest()->get();
        return view('pelanggan.dashboard', compact('tickets'));
    }

    /**
     * Form buat laporan baru
     */
    public function create()
    {
        return view('pelanggan.laporan.create');
    }

    /**
     * Menyimpan laporan ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'alamat_pelanggan' => 'required|string',
            'foto_kondisi'     => 'required|image|max:2048', 
        ]);

        $photoPath = null;
        if ($request->hasFile('foto_kondisi')) {
            $photoPath = $request->file('foto_kondisi')->store('tickets', 'public');
        }

        Ticket::create([
            'user_id'          => Auth::id(),
            'nomor_tiket'      => 'TKT-' . strtoupper(Str::random(8)), 
            'alamat_pelanggan' => $request->alamat_pelanggan,
            'foto_kondisi'     => $photoPath,
            'status'           => 'pending', 
        ]);

        return redirect()->route('pelanggan.dashboard')
            ->with('success', 'Laporan gangguan berhasil dikirim!');
    }

    /**
     * Fitur Monitoring/Tracking Progres Teknisi (REVISI LENGKAP)
     */
    public function track($id)
{
    // Pastikan ada 'updates' di dalam with()
    $ticket = Ticket::with(['technician', 'updates' => function($query) {
        $query->latest(); 
    }])->findOrFail($id);
    
    return view('pelanggan.laporan.track', compact('ticket'));
}
}