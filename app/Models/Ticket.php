<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // ✅ REVISI: dipakai untuk scope filter harian/mingguan/bulanan/tahunan
 
class Ticket extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'user_id',
        'technician_id',
        'nomor_tiket',
        'alamat_pelanggan',
        'foto_kondisi',
        'status',
        'progress_status',
        'penyebab',
        'action_taken',
        'description',
        'customer_id_pln'
    ];
 
    /**
     * Relasi ke History Updates (PENTING untuk Timeline)
     */
    public function updates()
    {
        return $this->hasMany(TicketUpdate::class);
    }
 
    /**
     * Relasi ke User sebagai Pelanggan
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
 
    /**
     * Relasi ke User sebagai Teknisi
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
 
    /**
     * Accessor untuk Label Status yang lebih manusiawi
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending'    => 'Menunggu Antrean',
            'assigned'   => 'Sudah Ditugaskan',
            'perjalanan' => 'Dalam Perjalanan',
            'lokasi'     => 'Sudah di Lokasi',
            'perbaikan'  => 'Proses Perbaikan',
            'selesai'    => 'Selesai / Normal',
            default      => $this->status,
        };
    }
 
    /**
     * Accessor untuk Warna Status (Disinkronkan dengan Label di atas)
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending'    => 'bg-gray-500',
            'assigned'   => 'bg-blue-400',
            'perjalanan' => 'bg-blue-600',  // Revisi dari on_the_way
            'lokasi'     => 'bg-indigo-500', // Revisi dari arrived
            'perbaikan'  => 'bg-orange-500', // Revisi dari repairing
            'selesai'    => 'bg-green-500',  // Revisi dari resolved
            default      => 'bg-gray-800',
        };
    }
 
    /*
    |--------------------------------------------------------------------------
    | ✅ REVISI BARU: SCOPE STATUS & FILTER WAKTU (TIMELINE / DATE RANGE)
    |--------------------------------------------------------------------------
    | Dipakai oleh Dashboard Manager, Histori Harian, dan Laporan Teknisi.
    | Daftar status ditulis ganda (Indonesia + Inggris) supaya data lama
    | yang masih memakai 'resolved' tetap ikut terhitung.
    */
 
    // Status yang dianggap SUDAH SELESAI dikerjakan
    public const STATUS_SELESAI = ['selesai', 'resolved', 'normal'];
 
    /** Tiket yang sudah selesai dikerjakan */
    public function scopeSelesai($query)
    {
        return $query->whereIn('status', self::STATUS_SELESAI);
    }
 
    /** Tiket yang BELUM selesai (pending, assigned, perjalanan, lokasi, perbaikan) */
    public function scopeBelumSelesai($query)
    {
        return $query->whereNotIn('status', self::STATUS_SELESAI);
    }
 
    /** Tiket pada tanggal hari ini */
    public function scopeHariIni($query, string $column = 'created_at')
    {
        return $query->whereDate($column, Carbon::today());
    }
 
    /**
     * Filter periode fleksibel: hari / minggu / bulan / tahun,
     * atau rentang tanggal manual (date range) start_date s/d end_date.
     */
    public function scopePeriode($query, ?string $periode, ?string $start = null, ?string $end = null, string $column = 'created_at')
    {
        // Prioritas 1: rentang tanggal manual dari form (date picker)
        if ($start && $end) {
            return $query->whereBetween($column, [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay(),
            ]);
        }
 
        if ($start && ! $end) {
            return $query->whereDate($column, Carbon::parse($start));
        }
 
        // Prioritas 2: pilihan cepat periode
        return match ($periode) {
            'hari'   => $query->whereDate($column, Carbon::today()),
            'minggu' => $query->whereBetween($column, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
            'bulan'  => $query->whereBetween($column, [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]),
            'tahun'  => $query->whereBetween($column, [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]),
            default  => $query, // 'semua' / kosong = tanpa filter waktu
        };
    }
 
    /** Apakah tiket ini sudah selesai? (dipakai di Blade) */
    public function getIsSelesaiAttribute(): bool
    {
        return in_array($this->status, self::STATUS_SELESAI);
    }
}