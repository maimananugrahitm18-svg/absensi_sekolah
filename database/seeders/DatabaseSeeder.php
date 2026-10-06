<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\Jadwal;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Tahun Ajaran ─────────────────────────────────────────────────────
        $tahun = TahunAjaran::create([
            'nama'            => '2024/2025',
            'semester'        => 'ganjil',
            'tanggal_mulai'   => '2024-07-15',
            'tanggal_selesai' => '2024-12-20',
            'is_aktif'        => true,
        ]);

        // ── Admin ─────────────────────────────────────────────────────────────
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // ── Kelas ─────────────────────────────────────────────────────────────
        $kelas10a = Kelas::create(['nama' => 'IPA 1', 'tingkat' => 'X',   'jurusan' => 'IPA', 'kapasitas' => 35, 'tahun_ajaran_id' => $tahun->id]);
        $kelas10b = Kelas::create(['nama' => 'IPS 1', 'tingkat' => 'X',   'jurusan' => 'IPS', 'kapasitas' => 35, 'tahun_ajaran_id' => $tahun->id]);
        $kelas11a = Kelas::create(['nama' => 'IPA 1', 'tingkat' => 'XI',  'jurusan' => 'IPA', 'kapasitas' => 35, 'tahun_ajaran_id' => $tahun->id]);
        $kelas12a = Kelas::create(['nama' => 'IPA 1', 'tingkat' => 'XII', 'jurusan' => 'IPA', 'kapasitas' => 35, 'tahun_ajaran_id' => $tahun->id]);

        // ── Mata Pelajaran ────────────────────────────────────────────────────
        $mapels = [
            ['kode' => 'MTK',  'nama' => 'Matematika',    'sks' => 4],
            ['kode' => 'BIN',  'nama' => 'Bahasa Indonesia', 'sks' => 3],
            ['kode' => 'BIG',  'nama' => 'Bahasa Inggris', 'sks' => 3],
            ['kode' => 'FIS',  'nama' => 'Fisika',         'sks' => 4],
            ['kode' => 'KIM',  'nama' => 'Kimia',          'sks' => 4],
            ['kode' => 'BIO',  'nama' => 'Biologi',        'sks' => 4],
            ['kode' => 'SEJ',  'nama' => 'Sejarah',        'sks' => 2],
            ['kode' => 'PKN',  'nama' => 'PKn',            'sks' => 2],
            ['kode' => 'PENJAS', 'nama' => 'Pendidikan Jasmani', 'sks' => 2],
        ];
        $createdMapels = [];
        foreach ($mapels as $m) {
            $createdMapels[$m['kode']] = MataPelajaran::create(array_merge($m, ['status' => 'aktif']));
        }

        // ── Guru ──────────────────────────────────────────────────────────────
        $guruData = [
            ['nama' => 'Budi Santoso, S.Pd',     'email' => 'guru@gmail.com',        'nip' => '198001012005011001'],
            ['nama' => 'Siti Rahayu, S.Pd',      'email' => 'siti@sekolah.sch.id',   'nip' => '198205152006042001'],
            ['nama' => 'Ahmad Fauzi, M.Pd',      'email' => 'ahmad@sekolah.sch.id',  'nip' => '197512202003121002'],
            ['nama' => 'Dewi Lestari, S.Pd',     'email' => 'dewi@sekolah.sch.id',   'nip' => '199001012015042001'],
        ];
        $createdGurus = [];
        foreach ($guruData as $g) {
            $user = User::create([
                'name'     => $g['nama'],
                'email'    => $g['email'],
                'password' => Hash::make('guru123'),
                'role'     => 'guru',
            ]);
            $createdGurus[] = Guru::create([
                'user_id'       => $user->id,
                'nip'           => $g['nip'],
                'nama'          => $g['nama'],
                'jenis_kelamin' => 'L',
                'status'        => 'aktif',
            ]);
        }
        $createdGurus[1]->update(['jenis_kelamin' => 'P']);
        $createdGurus[3]->update(['jenis_kelamin' => 'P']);

        // ── Siswa ─────────────────────────────────────────────────────────────
        $siswaData = [
            ['nama' => 'Andi Pratama',    'nis' => '240001', 'email' => 'siswa@gmail.com',     'kelas_id' => $kelas10a->id],
            ['nama' => 'Bela Safitri',    'nis' => '240002', 'email' => 'bela@siswa.sch.id',   'kelas_id' => $kelas10a->id],
            ['nama' => 'Candra Wijaya',   'nis' => '240003', 'email' => 'candra@siswa.sch.id', 'kelas_id' => $kelas10a->id],
            ['nama' => 'Dina Permata',    'nis' => '240004', 'email' => 'dina@siswa.sch.id',   'kelas_id' => $kelas10a->id],
            ['nama' => 'Eko Saputra',     'nis' => '240005', 'email' => 'eko@siswa.sch.id',    'kelas_id' => $kelas10b->id],
            ['nama' => 'Farah Nabila',    'nis' => '240006', 'email' => 'farah@siswa.sch.id',  'kelas_id' => $kelas10b->id],
            ['nama' => 'Gilang Ramadhan', 'nis' => '240007', 'email' => 'gilang@siswa.sch.id', 'kelas_id' => $kelas11a->id],
            ['nama' => 'Hana Salsabila',  'nis' => '240008', 'email' => 'hana@siswa.sch.id',   'kelas_id' => $kelas11a->id],
        ];
        foreach ($siswaData as $s) {
            $user = User::create([
                'name'     => $s['nama'],
                'email'    => $s['email'],
                'password' => Hash::make('siswa123'),
                'role'     => 'siswa',
            ]);
            Siswa::create([
                'user_id'  => $user->id,
                'nis'      => $s['nis'],
                'nama'     => $s['nama'],
                'kelas_id' => $s['kelas_id'],
                'status'   => 'aktif',
            ]);
        }

        // ── Jadwal ────────────────────────────────────────────────────────────
        $jadwalData = [
            ['kelas_id' => $kelas10a->id, 'guru_id' => $createdGurus[0]->id, 'mapel' => 'MTK',  'hari' => 'Senin',  'mulai' => '07:00', 'selesai' => '08:40'],
            ['kelas_id' => $kelas10a->id, 'guru_id' => $createdGurus[1]->id, 'mapel' => 'BIN',  'hari' => 'Senin',  'mulai' => '08:40', 'selesai' => '10:00'],
            ['kelas_id' => $kelas10a->id, 'guru_id' => $createdGurus[2]->id, 'mapel' => 'FIS',  'hari' => 'Selasa', 'mulai' => '07:00', 'selesai' => '08:40'],
            ['kelas_id' => $kelas10a->id, 'guru_id' => $createdGurus[3]->id, 'mapel' => 'BIG',  'hari' => 'Rabu',   'mulai' => '07:00', 'selesai' => '08:20'],
            ['kelas_id' => $kelas10b->id, 'guru_id' => $createdGurus[0]->id, 'mapel' => 'MTK',  'hari' => 'Selasa', 'mulai' => '08:40', 'selesai' => '10:20'],
            ['kelas_id' => $kelas11a->id, 'guru_id' => $createdGurus[2]->id, 'mapel' => 'FIS',  'hari' => 'Kamis',  'mulai' => '07:00', 'selesai' => '09:00'],
        ];

        foreach ($jadwalData as $j) {
            Jadwal::create([
                'kelas_id'          => $j['kelas_id'],
                'guru_id'           => $j['guru_id'],
                'mata_pelajaran_id' => $createdMapels[$j['mapel']]->id,
                'tahun_ajaran_id'   => $tahun->id,
                'hari'              => $j['hari'],
                'jam_mulai'         => $j['mulai'],
                'jam_selesai'       => $j['selesai'],
                'status'            => 'aktif',
            ]);
        }
    }
}
