<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $tickets = Ticket::with(['customer', 'technician'])
            ->latest()->get();

        $totalPending  = Ticket::where('status', 'pending')->count();
        $totalProses   = Ticket::whereIn('status', ['assigned','on_the_way','arrived','diagnosing','repairing'])->count();
        $totalSelesai  = Ticket::where('status', 'resolved')->count();
        $totalTeknisi  = User::where('role', 'teknisi')->count();

        // ✅ TAMBAHAN: notifikasi gangguan pending
        $notifications = Ticket::with('customer')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'tickets','totalPending','totalProses','totalSelesai','totalTeknisi','notifications'
        ));
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate(['technician_id' => 'required|exists:users,id']);

        $ticket->update([
            'technician_id' => $request->technician_id,
            'status'        => 'assigned',
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Tiket berhasil ditugaskan ke teknisi!');
    }

    public function users(Request $request)
    {
        // ✅ REVISI: pakai kolom role biasa, bukan Spatie
        $query = User::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->get();
        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users-create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:pelanggan,teknisi,admin,manager',
        ]);

        // ✅ REVISI: simpan role ke kolom, bukan Spatie
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil ditambahkan!');
    }

    // ✅ TAMBAHAN: hapus user
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                ->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil dihapus!');
    }

    public function laporan(Request $request)
    {
        $query = Ticket::with(['customer', 'technician']);

        if ($request->periode == 'minggu') {
            $query->where('created_at', '>=', Carbon::now()->startOfWeek());
        } elseif ($request->periode == 'bulan') {
            $query->where('created_at', '>=', Carbon::now()->startOfMonth());
        } elseif ($request->periode == 'tahun') {
            $query->where('created_at', '>=', Carbon::now()->startOfYear());
        }

        // ✅ TAMBAHAN: filter status
        if ($request->status) {
    if ($request->status == 'proses') {
        $query->whereIn('status', ['assigned','on_the_way','arrived','diagnosing','repairing']);
    } else {
        $query->where('status', $request->status);
    }
}

        $tickets = $query->latest()->get();
        return view('admin.laporan', compact('tickets'));
    }
}