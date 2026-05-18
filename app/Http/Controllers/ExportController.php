<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportExcel()
    {
        // Pastikan di dalam TicketsExport juga menggunakan query yang sama (with customer & technician)
        return Excel::download(new TicketsExport, 'laporan-gangguan-' . date('d-M-Y') . '.xlsx');
    }

    public function exportPdf()
    {
        // 1. Ambil data dengan filter yang sama seperti di halaman Laporan Admin (jika ada filter)
        // 2. Gunakan 'technician' sesuai nama relasi di Model Ticket yang merujuk ke 'technician_id'
        $tickets = Ticket::with(['customer', 'technician'])
                    ->latest()
                    ->get();
        
        // 3. Load view PDF
        $pdf = Pdf::loadView('exports.tickets_pdf', compact('tickets'));
        
        // 4. Set ukuran kertas landscape agar tabel yang lebar tidak terpotong
        return $pdf->setPaper('a4', 'landscape')
                   ->download('laporan-gangguan-' . date('d-M-Y') . '.pdf');
    }
}