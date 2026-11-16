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
        'start_time',
        'end_time',
        'payment_deadline',
        'paid_at',
        'check_in_at',
        'total_amount',
        'dp_amount',
        'remaining_amount',
        'payment_type',
        'payment_method_id',
        'payment_proof',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date'     => 'date',
        'total_amount'     => 'decimal:2',
        'dp_amount'        => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'payment_deadline' => 'datetime',
        'paid_at'          => 'datetime',
        'check_in_at'      => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'BKNG-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }

    public function getTeamNameAttribute(): string
    {
        // 1. Ekstrak dari format Walk-in (Nama Tim)
        if (!empty($this->notes) && preg_match('/Walk-in \((.*?)\)/i', $this->notes, $matches)) {
            return trim($matches[1]);
        }

        // 2. Jika ada nama tim di notes yang eksplisit (misal diawali "Tim ...")
        if (!empty($this->notes) && preg_match('/^(tim\s+[a-zA-Z0-9\s]+)/i', trim($this->notes), $m)) {
            return trim($m[1]);
        }

        // 3. Nama user / pemesan akun
        if ($this->user && !empty($this->user->name)) {
            return $this->user->name;
        }

        // 4. Fallback catatan langsung jika pendek
        if (!empty($this->notes) && strlen(trim($this->notes)) <= 40) {
            return trim($this->notes);
        }

        return 'Tim Pemesan';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // relasi ke metode pembayaran
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
