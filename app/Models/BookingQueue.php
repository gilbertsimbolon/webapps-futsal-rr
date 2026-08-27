<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingQueue extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_id',
        'booking_date',
        'start_time',
        'end_time',
        'user_id',
        'customer_name',
        'queue_order',
        'status',
        'quantum_start',
        'quantum_end',
    ];

    protected $casts = [
        'booking_date'  => 'date',
        'quantum_start' => 'datetime',
        'quantum_end'   => 'datetime',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
