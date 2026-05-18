<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Mengambil data yang ingin ditampilkan saja
        return Ticket::select('id', 'nomor_tiket', 'alamat_pelanggan', 'status', 'created_at')->get();
    }

    public function headings(): array
    {
        return ["ID", "Nomor Tiket", "Pelanggan", "Status", "Tanggal"];
    }
}