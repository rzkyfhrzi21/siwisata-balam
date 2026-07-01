<?php
/* ======================================================
   VIEW REKOMENDASI (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna/pengunjung web.
   
   Halaman ini adalah halaman rekomendasi wisata terdekat.
   Aplikasi akan meminta akses lokasi pengguna (GPS), lalu menghitung
   jarak dengan algoritma Haversine di Controller, dan menampilkan 
   destinasi wisata terdekat di halaman ini.
====================================================== */
?>
<?= $this->include('pengunjung/layout/header') ?>

<style>
    .btn-jelajah {
        background-color: #13357B;
        color: #ffffff !important;
        border: 1px solid #13357B;
        transition: all 0.3s;
    }

    .btn-jelajah:hover {
        background-color: #ffffff !important;
        color: #13357B !important;
    }
</style>
<!-- ======================================================
         BAGIAN HEADER HALAMAN (BREADCRUMB)
         
         Ini adalah area judul halaman besar di bagian atas,
         biasanya dilengkapi dengan jalur navigasi (breadcrumb)
         seperti "Home > Pages > Rekomendasi".
    ====================================================== -->
<div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h3 class="text-white display-3 mb-4"><?= $title ?? 'Page' ?></h3>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white"><?= $title ?? 'Page' ?></li>
        </ol>
    </div>
</div>

<!-- ======================================================
     BAGIAN REKOMENDASI WISATA TERDEKAT
     
     Ini adalah area utama halaman yang berisi tombol "Gunakan Lokasi Saya" 
     dan daftar kartu wisata hasil rekomendasi.
====================================================== -->
<div class="container-fluid packages py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Rekomendasi</h5>
            <h1 class="mb-3">Wisata Terdekat dari Lokasi Anda</h1>
            <p class="mb-4">Sistem akan menghitung jarak destinasi wisata dari posisi Anda menggunakan Algoritma Haversine. Izinkan akses lokasi agar fitur ini berfungsi.</p>

            <!-- ======================================================
                 TOMBOL MINTA LOKASI
                 Tombol ini ketika diklik akan memicu kode JavaScript 
                 di bagian bawah file untuk meminta akses GPS ke browser pengguna.
            ====================================================== -->
            <button id="btn-minta-lokasi" class="btn btn-primary rounded-pill py-3 px-5">
                <i class="fa fa-map-marker-alt me-2"></i>Gunakan Lokasi Saya
            </button>
            <div id="loading-lokasi" class="mt-3 d-none text-muted">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                Menghitung jarak wisata terdekat...
            </div>
            <div id="error-lokasi" class="mt-3 d-none text-danger">
                <i class="fa fa-exclamation-triangle me-2"></i>
                <span id="error-msg">Akses lokasi ditolak. Izinkan akses lokasi di browser Anda lalu coba lagi.</span>
            </div>
        </div>

        <!-- ======================================================
             HASIL REKOMENDASI (LOOPING KARTU WISATA)
             
             Bagian ini hanya akan muncul JIKA Controller mengirimkan data
             '$destinasi' (yang artinya posisi pengguna sudah didapatkan 
             dari parameter ?lat=...&lon=...).

             Penjelasan Looping (Perulangan):
             - Menggunakan perintah `foreach ($destinasi as $i => $d)` yang artinya:
               Sistem akan mengecek semua data wisata terdekat satu per satu.
               Untuk setiap 1 wisata, sistem akan membuat 1 buah kotak (kartu).
               
             Data/Kolom Database yang Digunakan ($d):
             - $d['nama_wisata']     : Menampilkan judul destinasi.
             - $d['thumbnail']       : Foto sampul wisata. Diambil dari folder server `uploads/thumbnail/`.
                                       Jika kosong, pakai gambar bawaan `assets/img/packages-1.jpg`.
             - $d['harga_tiket']     : Menampilkan harga tiket (Rp atau Gratis).
             - $d['alamat']          : Menampilkan lokasi alamat fisik wisata.
             - $d['jam_operasional'] : Menampilkan waktu buka-tutup wisata.
             - $d['jarak_km']        : (Bukan dari tabel DB asli). Ini adalah hasil kalkulasi jarak dalam satuan KM 
                                       yang diproses oleh Controller menggunakan fungsi di file `app/Libraries/Haversine.php`.
             - $d['deskripsi']       : Penjelasan singkat wisata.
             - $d['slug']            : Teks URL unik (misal "pantai-mutun") untuk tombol "Baca Detail".
             - $d['link_gmaps']      : Link Google Maps jika diisi admin.
        ====================================================== -->
        <div id="hasil-rekomendasi">
            <?php if (!empty($destinasi) && $user_lat): ?>
                <div class="packages-carousel owl-carousel">
                    <?php foreach ($destinasi as $i => $d): ?>
                        <div class="packages-item">
                            <div class="packages-img position-relative">
                                <img src="<?= $d['thumbnail'] ? base_url('uploads/thumbnail/' . $d['thumbnail']) : base_url('assets/img/packages-1.jpg') ?>"
                                    class="img-fluid w-100 rounded-top skeleton-effect"
                                    style="height: 250px; object-fit: cover;"
                                    loading="lazy" onload="this.classList.remove('skeleton-effect')"
                                    alt="<?= esc($d['nama_wisata']) ?>">
                                <div class="packages-info d-flex border border-start-0 border-end-0 position-absolute" style="width: 100%; bottom: 0; left: 0; z-index: 5;">
                                    <small class="flex-fill text-center border-end py-2 px-2 d-flex align-items-center justify-content-center" style="min-width: 0; width: 33.33%;">
                                        <i class="fa fa-ticket-alt me-2 flex-shrink-0"></i>
                                        <?php $hargaText = ($d['harga_tiket'] == 0 || strtolower($d['harga_tiket'] ?? '') == 'gratis' || empty($d['harga_tiket'])) ? 'Gratis' : (is_numeric($d['harga_tiket']) ? 'Rp ' . number_format($d['harga_tiket'], 0, ',', '.') : $d['harga_tiket']); ?>
                                        <span class="text-truncate" title="<?= esc($hargaText) ?>"><?= esc($hargaText) ?></span>
                                    </small>
                                    <small class="flex-fill text-center border-end py-2 px-2 d-flex align-items-center justify-content-center" style="min-width: 0; width: 33.33%;">
                                        <i class="fa fa-map-marker-alt me-2 flex-shrink-0"></i>
                                        <span class="text-truncate" title="<?= esc($d['alamat'] ?: '-') ?>"><?= esc($d['alamat'] ?: '-') ?></span>
                                    </small>
                                    <small class="flex-fill text-center py-2 px-2 d-flex align-items-center justify-content-center" style="min-width: 0; width: 33.33%;">
                                        <i class="fa fa-clock me-2 flex-shrink-0"></i>
                                        <span class="text-truncate" title="<?= esc($d['jam_operasional'] ?: '-') ?>"><?= esc($d['jam_operasional'] ?: '-') ?></span>
                                    </small>
                                </div>
                                <div class="packages-price py-2 px-4" style="width: fit-content; min-width: 100px; white-space: nowrap;">
                                    <?php if ($i === 0): ?>
                                        <span class="badge bg-warning text-dark me-1 border-0">Terdekat</span>
                                    <?php endif; ?>
                                    <?php $jarakText = ($d['jarak_km'] < 1) ? ($d['jarak_km'] * 1000) . ' Meter' : $d['jarak_km'] . ' KM'; ?>
                                    <i class="fa fa-route me-1"></i> <?= $jarakText ?>
                                </div>
                            </div>
                            <div class="packages-content bg-light">
                                <div class="p-4 pb-0">
                                    <h5 class="mb-0 text-truncate" title="<?= esc($d['nama_wisata']) ?>"><?= esc($d['nama_wisata']) ?></h5>
                                    <!-- ======================================================
                                         PENCARIAN NAMA KATEGORI (SUB-LOOPING)
                                         Looping `foreach ($kategori as $kat)` ini bertujuan mencari 
                                         apakah 'id' dari tabel kategori sama dengan 'kategori_id' 
                                         milik wisata ini. Jika cocok, ambil `nama_kategori`-nya.
                                    ====================================================== -->
                                    <?php
                                    $namaKategori = 'Umum';
                                    if (isset($kategori)) {
                                        foreach ($kategori as $kat) {
                                            if ($kat['id'] == $d['kategori_id']) {
                                                $namaKategori = $kat['nama_kategori'];
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <small class="text-uppercase text-primary"><?= esc($namaKategori) ?></small>
                                    <div class="mb-3">
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                    </div>
                                    <p class="mb-4" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; height: 72px;">
                                        <?= esc($d['deskripsi'] ?? 'Deskripsi tidak tersedia.') ?>
                                    </p>
                                </div>
                                <div class="row bg-primary rounded-bottom mx-0">
                                    <div class="col-6 text-start px-0">
                                        <a href="<?= base_url('wisata/' . $d['slug']) ?>" class="btn-jelajah btn py-2 px-4 w-100" style="border-radius: 0 0 0 10px;">Baca Detail</a>
                                    </div>
                                    <div class="col-6 text-end px-0">
                                        <?php
                                        $gmapsLink = !empty($d['link_gmaps']) ? $d['link_gmaps'] : "https://www.google.com/maps/dir/?api=1&destination={$d['latitude']},{$d['longitude']}";
                                        ?>
                                        <a href="<?= esc($gmapsLink) ?>" target="_blank" rel="noopener" class="btn-jelajah btn py-2 px-4 w-100" style="border-radius: 0 0 10px 0;">Menuju Lokasi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-12 text-center mt-5">
                    <a href="<?= base_url('peta') ?>" class="btn btn-primary rounded-pill py-3 px-5">
                        Lihat Semua Destinasi di Peta <i class="fa fa-arrow-right ms-2"></i>
                    </a>
                </div>
            <?php elseif (!$user_lat): ?>
                <!-- Placeholder sebelum user klik tombol lokasi -->
                <div class="text-center py-5 text-muted" id="placeholder-belum">
                    <i class="fa fa-map-marker-alt fa-4x mb-3 text-primary opacity-50"></i>
                    <p>Klik tombol di atas untuk menemukan wisata terdekat dari posisi Anda saat ini.</p>
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="fa fa-info-circle fa-2x mb-3"></i>
                    <p>Tidak ada destinasi dengan koordinat yang tersedia saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- ======================================================
     SKRIP JAVASCRIPT (GEOLOCATION & RE-ROUTING)
     
     Fungsi script di bawah ini adalah untuk "mengobrol" dengan sensor 
     GPS (Lokasi) bawaan dari browser HP/Laptop pengunjung.
     
     Alur Kerjanya:
     (1) Mendengarkan saat pengunjung klik tombol "Gunakan Lokasi Saya".
     (2) Mengecek apakah browser pengunjung mendukung fitur GPS.
     (3) Menampilkan tulisan loading (memutar).
     (4) Meminta izin secara diam-diam ke sistem untuk membaca titik koordinat saat ini.
     (5) Jika berhasil, halamannya "di-refresh" sendiri dengan menyelipkan 
         titik Latitude (lat) & Longitude (lon) ke atas URL: ?lat=...&lon=...
         (Hal inilah yang memicu Controller bekerja melakukan hitungan Haversine).
====================================================== -->
<script>
    // (1) Saat tombol diklik...
    document.getElementById('btn-minta-lokasi').addEventListener('click', function() {
        var loading = document.getElementById('loading-lokasi');
        var errDiv = document.getElementById('error-lokasi');
        var errMsg = document.getElementById('error-msg');

        // (2) Cek ketersediaan fitur lokasi di browser
        if (!navigator.geolocation) {
            errDiv.classList.remove('d-none');
            errMsg.textContent = 'Browser Anda tidak mendukung Geolocation.';
            return;
        }

        // (3) Tampilkan icon loading
        loading.classList.remove('d-none');
        errDiv.classList.add('d-none');
        document.getElementById('placeholder-belum') && document.getElementById('placeholder-belum').classList.add('d-none');

        // (4) Meminta kordinat asli ke sistem secara nyata (Real-time)
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                // (5) Berhasil dapat lokasi! Arahkan (Redirect) ke URL yang sama ditambah parameter koordinat
                var lat = pos.coords.latitude;
                var lon = pos.coords.longitude;
                window.location.href = '<?= base_url('rekomendasi') ?>?lat=' + lat + '&lon=' + lon;
            },
            function(err) {
                loading.classList.add('d-none');
                errDiv.classList.remove('d-none');
                switch (err.code) {
                    case 1:
                        errMsg.textContent = 'Akses lokasi ditolak. Izinkan akses di pengaturan browser Anda.';
                        break;
                    case 2:
                        errMsg.textContent = 'Lokasi tidak dapat ditentukan. Coba lagi.';
                        break;
                    case 3:
                        errMsg.textContent = 'Waktu habis. Coba lagi.';
                        break;
                }
            }, {
                timeout: 10000,
                enableHighAccuracy: true
            }
        );
    });
</script>

<?= $this->include('pengunjung/layout/footer') ?>