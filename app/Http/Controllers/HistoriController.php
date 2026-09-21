<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
 
/**
 * ✅ FILE BARU (REVISI)
 * --------------------------------------------------------------------------
 * Halaman Histori Harian + Timeline.
 * Bisa diakses SEMUA role (pelanggan, teknisi, admin, manager) dengan data
 * yang otomatis disesuaikan hak aksesnya:
 *  - pelanggan : hanya tiket miliknya sendiri
 *  - teknisi   : hanya tiket yang ditugaskan kepadanya
 *  - admin/manager : seluruh tiket
 *
 * Filter yang tersedia: hari ini / minggu ini / bulan ini / tahun ini
 * atau rentang tanggal manual (date range: start_date s/d end_date).
 */
class HistoriController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = strtolower($user->role);
 
        // Default: lihat data HARI INI (sesuai permintaan revisi)
        $periode = $request->periode ?? 'hari';
        $start   = $request->start_date;
        $end     = $request->end_date;
 
        /* ---------------- 1. QUERY TIKET SESUAI ROLE ---------------- */
        $query = Ticket::with(['customer', 'technician', 'updates.user']);
 
        if ($role === 'pelanggan') {
            $query->where('user_id', $user->id);
        } elseif ($role === 'teknisi') {
            $query->where('technician_id', $user->id);
        }
        // admin & manager: tanpa filter tambahan (lihat semua)
 
        /* ---------------- 2. FILTER WAKTU ---------------- */
        $query->periode($periode, $start, $end);
 
        /* ---------------- 3. FILTER STATUS ---------------- */
        if ($request->status === 'selesai') {
            $query->selesai();
        } elseif ($request->status === 'belum') {
            $query->belumSelesai();
        }
 
        $tickets = $query->latest('updated_at')->get();
 
        /* ---------------- 4. RINGKASAN ANGKA ---------------- */
        $ticketSelesai = $tickets->filter(fn ($t) => $t->is_selesai);
        $ticketBelum   = $tickets->reject(fn ($t) => $t->is_selesai);
 
        $jumlahSelesai = $ticketSelesai->count();
        $jumlahBelum   = $ticketBelum->count();
        $jumlahTotal   = $tickets->count();
 
        /* ---------------- 5. REKAP PENYEBAB GANGGUAN ---------------- */
        $penyebab = $ticketSelesai
            ->filter(fn ($t) => ! empty($t->penyebab))
            ->groupBy('penyebab')
            ->map->count()
            ->sortDesc();
 
        /* ---------------- 6. TIMELINE AKTIVITAS ---------------- */
        $timelineQuery = TicketUpdate::with(['ticket.customer', 'ticket.technician', 'user']);
 
        if ($role === 'pelanggan') {
            $timelineQuery->whereHas('ticket', fn ($q) => $q->where('user_id', $user->id));
        } elseif ($role === 'teknisi') {
            $timelineQuery->whereHas('ticket', fn ($q) => $q->where('technician_id', $user->id));
        }
 
        // Timeline memakai kolom created_at milik tabel ticket_updates
        $timelineQuery->periode($periode, $start, $end, 'created_at');
 
        // Dikelompokkan per tanggal supaya tampil sebagai garis waktu harian
        $timeline = $timelineQuery->latest()->get()
            ->groupBy(fn ($u) => $u->created_at->format('Y-m-d'));
 
        /* ---------------- 7. LABEL PERIODE UNTUK JUDUL ---------------- */
        $labelPeriode = match (true) {
            (bool) ($start && $end) => Carbon::parse($start)->translatedFormat('d M Y') . ' s/d ' . Carbon::parse($end)->translatedFormat('d M Y'),
            (bool) $start           => Carbon::parse($start)->translatedFormat('d M Y'),
            $periode === 'hari'     => 'Hari Ini — ' . Carbon::today()->translatedFormat('d M Y'),
            $periode === 'minggu'   => 'Minggu Ini',
            $periode === 'bulan'    => 'Bulan Ini',
            $periode === 'tahun'    => 'Tahun Ini',
            default                 => 'Semua Waktu',
        };
 
        return view('histori.index', compact(
            'tickets', 'ticketSelesai', 'ticketBelum',
            'jumlahSelesai', 'jumlahBelum', 'jumlahTotal',
            'penyebab', 'timeline', 'labelPeriode', 'periode', 'role'
        ));
    }
}