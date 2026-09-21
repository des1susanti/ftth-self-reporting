<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class TicketUpdate extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'ticket_id',
        'user_id',
        'status',
        'notes',
        'photo_path',
    ];
 
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
public function getStatusLabelAttribute()
{
    return match($this->status) {
        // Jika di DB 'perjalanan' atau 'on_the_way', tampilkan teks yang sama
        'perjalanan', 'on_the_way' => 'On the Way (Menuju Lokasi)',
        'lokasi', 'arrived'        => 'Arrived (Tiba di Lokasi)',
        'perbaikan', 'repairing'   => 'Repairing (Proses Perbaikan)',
        'selesai', 'resolved'      => 'Resolved (Jaringan Normal)',
        default                    => ucfirst($this->status),
    };
}
 
    /*
    |--------------------------------------------------------------------------
    | ✅ REVISI BARU: FILTER WAKTU UNTUK TIMELINE AKTIVITAS
    |--------------------------------------------------------------------------
    */
    public function scopePeriode($query, ?string $periode, ?string $start = null, ?string $end = null, string $column = 'created_at')
    {
        if ($start && $end) {
            return $query->whereBetween($column, [
                \Carbon\Carbon::parse($start)->startOfDay(),
                \Carbon\Carbon::parse($end)->endOfDay(),
            ]);
        }
 
        if ($start && ! $end) {
            return $query->whereDate($column, \Carbon\Carbon::parse($start));
        }
 
        return match ($periode) {
            'hari'   => $query->whereDate($column, \Carbon\Carbon::today()),
            'minggu' => $query->whereBetween($column, [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]),
            'bulan'  => $query->whereBetween($column, [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()]),
            'tahun'  => $query->whereBetween($column, [\Carbon\Carbon::now()->startOfYear(), \Carbon\Carbon::now()->endOfYear()]),
            default  => $query,
        };
    }}