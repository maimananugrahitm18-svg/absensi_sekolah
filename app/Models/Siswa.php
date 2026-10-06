<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nis', 'nisn', 'nama', 'kelas_id', 'jenis_kelamin',
        'tanggal_lahir', 'tempat_lahir', 'no_hp', 'no_hp_ortu', 'alamat', 'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function pengajuanIzins()
    {
        return $this->hasMany(PengajuanIzin::class);
    }

    public function getPersentaseKehadiranAttribute(): float
    {
        $total = $this->absensis()->count();
        if ($total === 0) return 0;
        $hadir = $this->absensis()->where('status', 'hadir')->count();
        return round(($hadir / $total) * 100, 1);
    }

    public function getRekap(): array
    {
        $hadir = $this->absensis()->where('status', 'hadir')->count();
        $izin  = $this->absensis()->where('status', 'izin')->count();
        $sakit = $this->absensis()->where('status', 'sakit')->count();
        $alpha = $this->absensis()->where('status', 'alpha')->count();
        $total = $this->absensis()->count();
        $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

        return [
            'hadir'      => $hadir,
            'izin'       => $izin,
            'sakit'      => $sakit,
            'alpha'      => $alpha,
            'total'      => $total,
            'persentase' => $persentase,
        ];
    }
}
