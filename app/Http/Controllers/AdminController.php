<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketUpdate; // ✅ REVISI: untuk timeline aktivitas harian
use Carbon\Carbon;
 
class AdminController extends Controller
{
    public function dashboard()
    {
        $tickets = Ticket::with(['customer', 'technician'])
            ->latest()->get();
 
        $totalPending  = Ticket::where('status', 'pending')->count();
        // ✅ REVISI: pakai scope agar status 'perjalanan/lokasi/perbaikan' (bahasa Indonesia) ikut terhitung
        $totalProses   = Ticket::belumSelesai()->where('status', '!=', 'pending')->count();
        $totalSelesai  = Ticket::selesai()->count();
        $totalTeknisi  = User::where('role', 'teknisi')->count();
 
        // ✅ TAMBAHAN: notifikasi gangguan pending
        $notifications = Ticket::with('customer')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
 
        /*
        |--------------------------------------------------------------------------
        | ✅ REVISI BARU: RINGKASAN HARI INI (untuk sisi MANAGER)
        |--------------------------------------------------------------------------
        | 1. Tiket selesai hari ini
        | 2. Tiket belum selesai (masih dikerjakan) hari ini
        | 3. Daftar penyebab gangguan hari ini
        | 4. Laporan penyelesaian gangguan hari ini
        | 5. Timeline aktivitas teknisi hari ini
        */
        $today = Carbon::today();
 
        // Laporan yang masuk hari ini
        $masukHariIni = Ticket::whereDate('created_at', $today)->count();
 
        // Tiket yang SELESAI hari ini (patokan: tanggal terakhir diupdate teknisi)
        $selesaiHariIni = Ticket::selesai()
            ->whereDate('updated_at', $today)
            ->count();
 
        // Tiket yang BELUM selesai / masih dikerjakan sampai hari ini
        $belumSelesaiHariIni = Ticket::belumSelesai()
            ->whereDate('created_at', '<=', $today)
            ->count();
 
        // Detail laporan penyelesaian gangguan hari ini (penyebab + tindakan)
        $laporanSelesaiHariIni = Ticket::with(['customer', 'technician'])
            ->selesai()
            ->whereDate('updated_at', $today)
            ->latest('updated_at')
            ->get();
 
        // Rekap penyebab gangguan hari ini -> ['Kabel putus' => 3, 'ODP rusak' => 1]
        $penyebabHariIni = $laporanSelesaiHariIni
            ->filter(fn ($t) => ! empty($t->penyebab))
            ->groupBy('penyebab')
            ->map->count()
            ->sortDesc();
 
        // Timeline aktivitas hari ini (dari tabel ticket_updates)
        $timelineHariIni = TicketUpdate::with(['ticket.customer', 'user'])
            ->whereDate('created_at', $today)
            ->latest()
            ->get();
 
        return view('admin.dashboard', compact(
            'tickets','totalPending','totalProses','totalSelesai','totalTeknisi','notifications',
            // ✅ REVISI BARU: variabel ringkasan harian
            'masukHariIni','selesaiHariIni','belumSelesaiHariIni',
            'laporanSelesaiHariIni','penyebabHariIni','timelineHariIni'
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
 
        /*
        | ✅ REVISI BARU: filter periode dipindah ke scopePeriode() milik Model Ticket
        | sehingga mendukung: hari ini / minggu / bulan / tahun / rentang tanggal manual.
        | Kode lama (minggu, bulan, tahun) tetap berfungsi sama persis.
        */
        $query->periode(
            $request->periode,
            $request->start_date,
            $request->end_date
        );
 
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