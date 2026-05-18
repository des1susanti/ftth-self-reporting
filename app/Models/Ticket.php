<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}