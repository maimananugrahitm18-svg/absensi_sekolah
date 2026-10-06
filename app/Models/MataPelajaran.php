<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $fillable = ['kode', 'nama', 'deskripsi', 'sks', 'status'];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
