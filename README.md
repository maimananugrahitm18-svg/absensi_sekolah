# 📚 Sistem Absensi Sekolah

Aplikasi manajemen absensi siswa dan guru berbasis web, dibangun dengan **Laravel 10** dan **Bootstrap 5**.

---

## ✨ Fitur Utama

### 👨‍💼 Admin
- Dashboard statistik kehadiran real-time
- Manajemen data siswa, guru, kelas, mata pelajaran, jadwal
- Rekap absensi **per kelas** (dikelompokkan rapi, dengan subtotal & rata-rata kehadiran)
- Export rekap ke Excel (.xls)
- Cetak laporan kehadiran
- Verifikasi pengajuan izin/sakit siswa
- Manajemen tahun ajaran & semester

### 👩‍🏫 Guru
- Input absensi siswa per jadwal mengajar
- Presensi kehadiran diri sendiri
- Lihat rekap absensi kelas yang diajar

### 🧑‍🎓 Siswa
- Pengajuan izin / sakit dengan keterangan lengkap
- Riwayat pengajuan izin (status: menunggu / disetujui / ditolak)
- Lihat riwayat absensi diri sendiri

---

## 🛠️ Tech Stack

| Teknologi | Versi |
|---|---|
| PHP | >= 8.1 |
| Laravel | 10.x |
| MySQL | 5.7+ / 8.x |
| Bootstrap | 5.3 |
| FontAwesome | 6.x |

---

## 🚀 Cara Instalasi Lokal

### 1. Clone repositori
```bash
git clone https://github.com/USERNAME/absensi-sekolah.git
cd absensi-sekolah
```

### 2. Install dependensi PHP
```bash
composer install
```

### 3. Salin file environment
```bash
cp .env.example .env
```

### 4. Generate APP_KEY
```bash
php artisan key:generate
```

### 5. Konfigurasi database di `.env`
```env
DB_DATABASE=absensi_sekolah
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan migrasi & seeder
```bash
php artisan migrate --seed
```

### 7. Buat storage symlink
```bash
php artisan storage:link
```

### 8. Jalankan server lokal
```bash
php artisan serve
```

Akses di: `http://127.0.0.1:8000`

---

## 🔑 Akun Default (Seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@sekolah.com | password |
| Guru | guru@sekolah.com | password |
| Siswa | siswa@sekolah.com | password |

> ⚠️ Ganti password setelah login pertama!

---

## 🌐 Deploy ke Hosting (cPanel / Shared Hosting)

1. Upload seluruh file proyek ke server (kecuali `/vendor`, `/node_modules`)
2. Jalankan `composer install --no-dev --optimize-autoloader` via SSH atau terminal hosting
3. Salin `.env.example` → `.env` dan isi sesuai konfigurasi hosting
4. Jalankan `php artisan key:generate`
5. Jalankan `php artisan migrate`
6. Jalankan `php artisan storage:link`
7. Pastikan folder `storage/` dan `bootstrap/cache/` writable (chmod 775)

---

## 📁 Struktur Penting

```
app/
├── Http/Controllers/
│   ├── Admin/          # Controller admin (rekap, siswa, guru, jadwal, dll)
│   ├── Guru/           # Controller guru (absensi, presensi)
│   └── Siswa/          # Controller siswa (pengajuan izin)
├── Models/             # Eloquent models
resources/views/
├── admin/              # Tampilan panel admin
├── guru/               # Tampilan panel guru
├── siswa/              # Tampilan panel siswa
└── layouts/            # Layout utama & auth
database/migrations/    # Struktur database
```

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan sekolah. Hak cipta © 2024.
