<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama', 'tingkat', 'jurusan', 'wali_kelas', 'kapasitas', 'tahun_ajaran_id'];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function getNamaLengkapAttribute(): string
    {
        return $this->tingkat . ' ' . $this->nama;
    }
}
