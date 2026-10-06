<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = ['jadwal_id', 'siswa_id', 'guru_id', 'tahun_ajaran_id', 'tanggal', 'status', 'keterangan'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'hadir' => '<span class="badge badge-hadir">Hadir</span>',
            'izin'  => '<span class="badge badge-izin">Izin</span>',
            'sakit' => '<span class="badge badge-sakit">Sakit</span>',
            'alpha' => '<span class="badge badge-alpha">Alpha</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
