<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'field_name',
        'slug',
        'field_type',
        'price_per_hour',
        'image',
        'description',
        'status',
    ];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
    ];

    // Otomatis generate slug sebelum simpan data baru
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($field) {
            if (empty($field->slug)) {
                $field->slug = Str::slug($field->field_name) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    // Relasi ke Cabang
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
