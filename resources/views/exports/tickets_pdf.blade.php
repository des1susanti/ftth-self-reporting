<!DOCTYPE html>
<html>
<head>
    <title>Laporan Gangguan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; }
        h2 { text-align: center; color: #333; margin-bottom: 5px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>DATA LAPORAN GANGGUAN</h2>
    <p class="text-center">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID Tiket</th>
                <th>Pelanggan</th> <th>Alamat</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
            <tr>
                <td>#FTTH-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</td>
                {{-- Gunakan customer->name agar muncul nama orangnya, bukan nomor tiket --}}
                <td>{{ $ticket->customer->name ?? 'N/A' }}</td> 
                <td>{{ $ticket->alamat_pelanggan }}</td>
                <td>{{ strtoupper($ticket->status) }}</td>
                <td>{{ $ticket->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Data Laporan Kosong</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>