<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'branch_id',
        'field_id',
        'schedule_id',
        'booking_date',
        'total_amount',
        'payment_method',
        'payment_proof',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    // Otomatis generate booking_code unik sebelum simpan
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'BKNG-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }

    // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke cabang
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // relasi ke lapangan
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    // relasi ke jadwal
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
