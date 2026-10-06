<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'semester', 'tanggal_mulai', 'tanggal_selesai', 'is_aktif'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_aktif' => 'boolean',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public static function aktif()
    {
        return static::where('is_aktif', true)->first();
    }

    public function getLabelAttribute(): string
    {
        return $this->nama . ' - Semester ' . ucfirst($this->semester);
    }
}
