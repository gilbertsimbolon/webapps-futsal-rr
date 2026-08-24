<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_name',
        'slug',
        'phone',
        'address',
        'description',
        'status',
    ];

    // membuat slug otomatis
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($branch) {
            if (empty($branch->slug)) {
                $branch->slug = Str::slug($branch->branch_name) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    // relasi ke user untuk owner cabang
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke lapangan
    public function fields()
    {
        return $this->hasMany(Field::class);
    }
}
