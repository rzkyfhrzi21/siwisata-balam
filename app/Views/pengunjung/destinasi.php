<?php
/* ======================================================
   VIEW DAFTAR DESTINASI (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna/pengunjung web.
   
   Halaman ini berfungsi sebagai Katalog/Daftar yang menampilkan 
   semua destinasi wisata secara lengkap.
   Dilengkapi juga dengan fitur Filter (pencarian nama, kategori, fasilitas).
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
     seperti "Home > Pages > Destinasi".
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
     BAGIAN DAFTAR WISATA DAN FILTER
====================================================== -->
<div class="container-fluid destination packages py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Destinasi</h5>
            <h1 class="mb-0">Daftar Destinasi Wisata Bandar Lampung</h1>
        </div>

        <!-- ======================================================
             BAGIAN FORMULIR PENCARIAN & FILTER
             
             Formulir ini bertugas mengirimkan data pilihan pengguna ke Controller 
             lewat jalur URL (method GET).
             
             Kolom Isian (Input) yang tersedia:
             - Cari Nama (name="search")    : Opsional. Berupa teks.
             - Kategori (name="kategori")   : Opsional. Pilihan dropdown (Looping dari $kategori).
             - Fasilitas (name="fasilitas") : Opsional. Pilihan dropdown (Looping dari $semua_fas).
        ====================================================== -->
        <form method="GET" action="<?= base_url('destinasi') ?>" class="mb-5">
            <div class="row g-3 justify-content-center align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Cari Nama Wisata</label>
                    <input type="text" name="search" class="form-control rounded-pill border border-primary"
                        placeholder="Cari..."
                        value="<?= esc($filter_search ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filter Kategori</label>
                    <select name="kategori" class="form-select rounded-pill border border-primary">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['slug'] ?>"
                                <?= ($filter_kat == $k['slug']) ? 'selected' : '' ?>>
                                <?= esc($k['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filter Fasilitas</label>
                    <select name="fasilitas" class="form-select rounded-pill border border-primary">
                        <option value="">Semua Fasilitas</option>
                        <?php foreach ($semua_fas as $f): ?>
                            <option value="<?= $f['slug'] ?>"
                                <?= ($filter_fas == $f['slug']) ? 'selected' : '' ?>>
                                <?= esc($f['nama_fasilitas']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary rounded-pill py-2 px-4 w-100">
                        <i class="fa fa-search me-1"></i> Filter
                    </button>
                </div>
                <?php if ($filter_kat || $filter_fas || (!empty($filter_search))): ?>
                    <div class="col-md-1">
                        <a href="<?= base_url('destinasi') ?>" class="btn btn-outline-secondary rounded-pill py-2 px-3 w-100" title="Reset">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </form>

        <!-- ======================================================
             BAGIAN DAFTAR DESTINASI (LOOPING)
             
             Bagian ini hanya akan muncul jika Controller mengirimkan 
             variabel `$destinasi` (artinya datanya ada).

             Penjelasan Looping:
             - `foreach ($destinasi as $d)` : Mencetak kotak wisata satu per satu.
             
             Kolom Database yang Digunakan:
             - $d['nama_wisata']     : Nama lokasi.
             - $d['thumbnail']       : Foto utama (Dari folder `uploads/thumbnail/`).
             - $d['alamat']          : Lokasi fisik.
             - $d['hari_operasional']: Waktu hari buka (misal: Senin - Minggu).
             - $d['jam_operasional'] : Jam buka (misal: 08:00 - 17:00).
             - $d['harga_tiket']     : Biaya masuk.
             - $d['deskripsi']       : Penjelasan singkat.
             - $d['slug']            : URL unik menuju Detail.
             - $d['latitude'] & $d['longitude'] : Untuk hitung link Gmaps otomatis.
        ====================================================== -->
        <?php if (!empty($destinasi)): ?>
            <div class="row g-4">
                <?php foreach ($destinasi as $d): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="packages-item">
                            <div class="packages-img">
                                <img src="<?= $d['thumbnail'] ? base_url('uploads/thumbnail/' . $d['thumbnail']) : base_url('assets/img/packages-1.jpg') ?>" 
                                     class="img-fluid w-100 rounded-top skeleton-effect" 
                                     style="height: 250px; object-fit: cover;"
                                     loading="lazy" onload="this.classList.remove('skeleton-effect')"
                                     alt="<?= esc($d['nama_wisata']) ?>">
                                <div class="packages-info d-flex border border-start-0 border-end-0 position-absolute" style="width: 100%; bottom: 0; left: 0; z-index: 5;">
                                    <small class="flex-fill text-center border-end py-2 px-2 d-flex align-items-center justify-content-center" style="min-width: 0; width: 33.33%;">
                                        <i class="fa fa-map-marker-alt me-2 flex-shrink-0"></i>
                                        <span class="text-truncate"><?= esc($d['alamat'] ?: '-') ?></span>
                                    </small>
                                    <small class="flex-fill text-center border-end py-2 px-2 d-flex align-items-center justify-content-center" style="min-width: 0; width: 33.33%;">
                                        <i class="fa fa-calendar-alt me-2 flex-shrink-0"></i>
                                        <span class="text-truncate"><?= esc($d['hari_operasional'] ?: '-') ?></span>
                                    </small>
                                    <small class="flex-fill text-center py-2 px-2 d-flex align-items-center justify-content-center" style="min-width: 0; width: 33.33%;">
                                        <i class="fa fa-clock me-2 flex-shrink-0"></i>
                                        <span class="text-truncate"><?= esc($d['jam_operasional'] ?: '-') ?></span>
                                    </small>
                                </div>
                                <div class="packages-price py-2 px-4" style="width: fit-content; min-width: 100px; white-space: nowrap;">
                                    <?= esc($d['harga_tiket'] == 0 || strtolower($d['harga_tiket'] ?? '') == 'gratis' || empty($d['harga_tiket']) ? 'Gratis' : (is_numeric($d['harga_tiket']) ? 'Rp ' . number_format($d['harga_tiket'], 0, ',', '.') : $d['harga_tiket'])) ?>
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
                                    foreach($kategori as $kat) {
                                        if($kat['id'] == $d['kategori_id']) {
                                            $namaKategori = $kat['nama_kategori'];
                                            break;
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
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa fa-map-marked-alt fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada destinasi yang sesuai filter.</h5>
                <a href="<?= base_url('destinasi') ?>" class="btn btn-primary rounded-pill mt-3">Lihat Semua Destinasi</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->include('pengunjung/layout/footer') ?>
