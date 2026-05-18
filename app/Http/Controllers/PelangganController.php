<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class PelangganController extends Controller
{
    public function dashboard()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('pelanggan.dashboard', compact('tickets'));
    }

    public function profil()
    {
        $user = auth()->user();
        $tickets = Ticket::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('pelanggan.profil', compact('user', 'tickets'));
    }
}