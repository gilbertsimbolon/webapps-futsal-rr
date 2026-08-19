<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'user_id',
        'is_active',
    ];

    // relasi ke one to many ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
