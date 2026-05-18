<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TeknisiController extends Controller
{
    public function dashboard()
    {
        $tickets = Ticket::with(['customer', 'updates'])
            ->where('technician_id', Auth::id()) 
            ->latest('updated_at')
            ->get();

        return view('teknisi.dashboard', compact('tickets'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        try {
            // 1. Validasi Input
            $request->validate([
                'status' => 'required|in:pending,assigned,perjalanan,lokasi,perbaikan,selesai,normal',
                'notes'  => 'nullable|string',
                'photo'  => 'nullable|image|max:2048',
                // Wajib diisi jika status selesai atau normal
                'penyebab' => 'required_if:status,selesai,normal',
                'action_taken' => 'required_if:status,selesai,normal',
            ]);

            // 2. Proses Upload Foto
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('updates', 'public');
            }

            // 3. REKAM JEJAK (Timeline untuk halaman pelanggan)
            TicketUpdate::create([
                'ticket_id'  => $ticket->id,
                'user_id'    => Auth::id(),
                'status'     => $request->status,
                'notes'      => $request->notes ?? "Status diperbarui menjadi " . $request->status,
                'photo_path' => $photoPath,
            ]);

            // 4. UPDATE TABEL UTAMA (Status Tiket)
            $dataUpdate = [
                'status' => $request->status,
            ];

            // Simpan data penyebab dan tindakan jika status selesai/normal
            if ($request->status == 'selesai' || $request->status == 'normal') {
                $dataUpdate['penyebab'] = $request->penyebab;
                $dataUpdate['action_taken'] = $request->action_taken;
            }

            // Update foto kondisi terakhir jika ada upload baru
            if ($photoPath) {
                $dataUpdate['foto_kondisi'] = $photoPath;
            }

            $ticket->update($dataUpdate);

            return redirect()->route('teknisi.dashboard')
                ->with('success', 'Status updated successfully to ' . $request->status);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal (misal: penyebab tidak diisi), kembali dengan pesan error
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Jika ada error sistem lainnya
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function laporan(Request $request)
    {
        $query = Ticket::with(['customer', 'updates'])
            ->where('technician_id', Auth::id());

        if ($request->periode == 'minggu') {
            $query->where('created_at', '>=', Carbon::now()->startOfWeek());
        } elseif ($request->periode == 'bulan') {
            $query->where('created_at', '>=', Carbon::now()->startOfMonth());
        } elseif ($request->periode == 'tahun') {
            $query->where('created_at', '>=', Carbon::now()->startOfYear());
        }

        $tickets = $query->latest()->get();
        return view('teknisi.laporan', compact('tickets'));
    }
}