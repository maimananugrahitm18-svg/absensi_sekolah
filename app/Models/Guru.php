<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nip', 'nama', 'jenis_kelamin', 'no_hp', 'alamat', 'pendidikan_terakhir', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function absensiHarian()
    {
        return $this->hasMany(AbsensiGuru::class);
    }
}
