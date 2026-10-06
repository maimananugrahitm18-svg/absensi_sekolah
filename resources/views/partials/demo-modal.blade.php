<!-- Modal Tutorial Mode Demo -->
<div class="modal fade" id="modalTutorialDemo" tabindex="-1" aria-labelledby="modalTutorialDemoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 p-4 pb-3" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: #fff;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 48px; height: 48px; background: rgba(255,255,255,.15); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                        🚀
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="modalTutorialDemoLabel">Panduan Uji Coba Demo Sistem</h5>
                        <p class="mb-0 small text-light opacity-75">Pelajari cara menjelajahi dan menguji seluruh fitur sistem absensi ini</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-3">
                <!-- Nav Pills Role -->
                <ul class="nav nav-pills nav-fill mb-3 p-1 rounded-3" style="background: #f1f5f9;" id="demoTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold small py-2" id="tab-admin" data-bs-toggle="pill" data-bs-target="#content-admin" type="button" role="tab">
                            👑 1. Peran Admin
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold small py-2" id="tab-guru" data-bs-toggle="pill" data-bs-target="#content-guru" type="button" role="tab">
                            👨‍🏫 2. Peran Guru
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold small py-2" id="tab-siswa" data-bs-toggle="pill" data-bs-target="#content-siswa" type="button" role="tab">
                            🎓 3. Peran Siswa
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="demoTabContent">
                    <!-- Tab Admin -->
                    <div class="tab-pane fade show active" id="content-admin" role="tabpanel">
                        <div class="p-3 rounded-3 mb-3" style="background: #fef2f2; border: 1px solid #fee2e2;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-danger mb-1">Akun Administrator</span>
                                    <div class="fw-semibold text-danger">Akses Penuh Pengelola Sekolah</div>
                                </div>
                                <a href="{{ route('demo.login', ['role' => 'admin']) }}" class="btn btn-sm btn-danger px-3 rounded-pill text-decoration-none">
                                    <i class="fas fa-sign-in-alt me-1"></i> Langsung Masuk Admin
                                </a>
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-tasks text-primary me-2"></i>Fitur yang dapat diuji coba:</h6>
                        <div class="row g-2 small text-muted">
                            <div class="col-md-6">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Manajemen Data Master:</strong> Tambah, edit, hapus data Siswa, Guru, Kelas, dan Mapel.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Jadwal Pelajaran:</strong> Setting jadwal harian per kelas, guru, dan jam mengajar.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Pantau Presensi Guru:</strong> Monitor jam masuk & pulang guru hari ini + Export Excel.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Pantau Izin Siswa:</strong> Memantau status seluruh permohonan izin & sakit siswa yang diverifikasi/ACC oleh Guru.</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Rekap Absensi Siswa:</strong> Filter kehadiran per kelas/tanggal, lihat persentase hadir, dan Export CSV/Excel.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Guru -->
                    <div class="tab-pane fade" id="content-guru" role="tabpanel">
                        <div class="p-3 rounded-3 mb-3" style="background: #eff6ff; border: 1px solid #dbeafe;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary mb-1">Akun Guru Pengajar</span>
                                    <div class="fw-semibold text-primary">Akses Pengajar & Absensi Kelas</div>
                                </div>
                                <a href="{{ route('demo.login', ['role' => 'guru']) }}" class="btn btn-sm btn-primary px-3 rounded-pill text-decoration-none">
                                    <i class="fas fa-sign-in-alt me-1"></i> Langsung Masuk Guru
                                </a>
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-tasks text-primary me-2"></i>Fitur yang dapat diuji coba:</h6>
                        <div class="row g-2 small text-muted">
                            <div class="col-md-6">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Presensi Mandiri Guru:</strong> Uji coba klik "Absen Masuk" dan "Absen Pulang" harian secara real-time.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Input Absensi Siswa:</strong> Catat kehadiran siswa per kelas (Hadir, Sakit, Izin, Alpha).</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Persetujuan Izin Siswa:</strong> Tinjau permohonan surat izin/sakit siswa dan berikan keputusan ACC atau Tolak.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Rekap Kelas:</strong> Pantau statistik kehadiran siswa di kelas yang Anda ampu.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Siswa -->
                    <div class="tab-pane fade" id="content-siswa" role="tabpanel">
                        <div class="p-3 rounded-3 mb-3" style="background: #f0fdf4; border: 1px solid #dcfce7;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-success mb-1">Akun Siswa / Murid</span>
                                    <div class="fw-semibold text-success">Akses Siswa & Pengajuan Izin</div>
                                </div>
                                <a href="{{ route('demo.login', ['role' => 'siswa']) }}" class="btn btn-sm btn-success px-3 rounded-pill text-decoration-none">
                                    <i class="fas fa-sign-in-alt me-1"></i> Langsung Masuk Siswa
                                </a>
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-tasks text-primary me-2"></i>Fitur yang dapat diuji coba:</h6>
                        <div class="row g-2 small text-muted">
                            <div class="col-md-6">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Dashboard Siswa:</strong> Cek ringkasan persentase kehadiran dan status diri.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Riwayat Presensi:</strong> Lihat histori absensi kehadiran per mata pelajaran.</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-2 border rounded-2 bg-light d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div><strong>Pengajuan Izin Online:</strong> Kirim formulir permohonan izin/sakit langsung secara praktis tanpa perlu repot unggah berkas foto.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalBeliSourceCode">
                    <i class="fas fa-shopping-cart me-1"></i> Ingin Beli Source Code Ini?
                </button>
                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Tutup Panduan</button>
            </div>
        </div>
    </div>
</div>
