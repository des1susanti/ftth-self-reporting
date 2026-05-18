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
}