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

<!-- About Start -->
<div class="container-fluid about py-5">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="h-100 p-5 d-flex align-items-center justify-content-center">
                    <img src="<?= base_url('logo.svg') ?>" class="img-fluid w-100 skeleton-effect" style="object-fit: contain; max-height: 400px;" alt="Tentang SiWisata Balam" loading="lazy" onload="this.classList.remove('skeleton-effect')">
                </div>
            </div>
            <div class="col-lg-7" style="background: linear-gradient(rgba(255, 255, 255, .9), rgba(255, 255, 255, .9)), url(<?= base_url('logo.svg') ?>) center center no-repeat; background-size: cover;">
                <h5 class="section-about-title pe-3">Tentang Sistem</h5>
                <h1 class="mb-4">Selamat Datang di <span class="text-primary">SiWisata Balam</span></h1>
                <p class="mb-4">Sistem Informasi Pariwisata Kota Bandar Lampung (SiWisata Balam) hadir untuk memudahkan Anda dalam menemukan dan menjelajahi berbagai destinasi wisata menarik di Kota Tapis Berseri.</p>
                <p class="mb-4">Kami menyediakan informasi terlengkap mulai dari harga tiket, jam operasional, fasilitas unggulan, hingga peta interaktif berbasis WebGIS yang akan memandu perjalanan liburan Anda menjadi lebih terencana dan menyenangkan.</p>
                <div class="row gy-2 gx-4 mb-4">
                    <div class="col-sm-6">
                        <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Informasi Akurat</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Peta Interaktif WebGIS</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Rekomendasi Terdekat</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Galeri Media HD</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Harga Tiket Transparan</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Selalu Diperbarui</p>
                    </div>
                </div>
                <a class="btn btn-primary rounded-pill py-3 px-5 mt-2" href="<?= base_url('tentang') ?>">Selengkapnya</a>
            </div>
        </div>
    </div>
</div>
<!-- About End -->

<!-- Services Start -->
<div class="container-fluid bg-light service py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Layanan Kami</h5>
            <h1 class="mb-0">Fitur Unggulan SiWisata Balam</h1>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="service-content-inner d-flex align-items-center bg-white border border-primary rounded p-4 pe-0">
                            <div class="service-content text-end">
                                <h5 class="mb-4">Peta WebGIS Interaktif</h5>
                                <p class="mb-0">Jelajahi seluruh persebaran lokasi pariwisata di Kota Bandar Lampung secara akurat dan responsif dengan peta interaktif yang mudah digunakan.</p>
                            </div>
                            <div class="service-icon p-4">
                                <i class="fa fa-map-marked-alt fa-4x text-primary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="service-content-inner d-flex align-items-center bg-white border border-primary rounded p-4 pe-0">
                            <div class="service-content text-end">
                                <h5 class="mb-4">Rekomendasi Terdekat</h5>
                                <p class="mb-0">Fitur penunjang cerdas menggunakan Algoritma Haversine untuk mencari dan merekomendasikan destinasi wisata terdekat langsung dari lokasi Anda saat ini.</p>
                            </div>
                            <div class="service-icon p-4">
                                <i class="fa fa-street-view fa-4x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="service-content-inner d-flex align-items-center bg-white border border-primary rounded p-4 ps-0">
                            <div class="service-icon p-4">
                                <i class="fa fa-images fa-4x text-primary"></i>
                            </div>
                            <div class="service-content">
                                <h5 class="mb-4">Galeri Media HD</h5>
                                <p class="mb-0">Lihat lebih dekat pesona wisata Bandar Lampung melalui kumpulan galeri foto berkualitas tinggi yang selalu diperbarui oleh pengelola.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="service-content-inner d-flex align-items-center bg-white border border-primary rounded p-4 ps-0">
                            <div class="service-icon p-4">
                                <i class="fa fa-ticket-alt fa-4x text-primary"></i>
                            </div>
                            <div class="service-content">
                                <h5 class="mb-4">Info Tiket & Integrasi WA</h5>
                                <p class="mb-0">Dapatkan informasi harga tiket dan jam operasional yang transparan, dilengkapi kemudahan komunikasi langsung via WhatsApp ke admin wisata.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Services End -->

<!-- Packages Start -->
<div class="container-fluid packages py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Destinasi</h5>
            <h1 class="mb-0">Destinasi Terbaru</h1>
        </div>
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
    </div>
</div>
<!-- Packages End -->

<!-- Gallery Start -->
<div class="container-fluid gallery py-5 my-5">
    <div class="mx-auto text-center mb-5" style="max-width: 900px;">
        <h5 class="section-title px-3">Galeri Kami</h5>
        <h1 class="mb-4">Eksplorasi Keindahan Kota.</h1>
        <p class="mb-0">Melihat cuplikan pemandangan terbaik dan fasilitas yang ditawarkan oleh setiap destinasi melalui lensa kamera.</p>
    </div>
    <div class="tab-class text-center">
        <ul class="nav nav-pills d-inline-flex justify-content-center mb-5">
            <li class="nav-item">
                <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill active" data-bs-toggle="pill" href="#GalleryTab-All">
                    <span class="text-dark" style="width: 150px;">Semua</span>
                </a>
            </li>
            <?php foreach ($kategori as $k): ?>
                <li class="nav-item">
                    <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#GalleryTab-<?= $k['id'] ?>">
                        <span class="text-dark" style="width: 150px;"><?= esc($k['nama_kategori']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content">
            <!-- Tab Semua -->
            <div id="GalleryTab-All" class="tab-pane fade show p-0 active">
                <div class="row g-2">
                    <?php
                    $limitAll = 0;
                    foreach ($semuaGaleri as $g):
                        if ($limitAll >= 8) break;
                        $limitAll++;
                    ?>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="gallery-item h-100">
                                <img src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" class="img-fluid w-100 h-100 rounded skeleton-effect" style="object-fit: cover; min-height: 200px;" loading="lazy" onload="this.classList.remove('skeleton-effect')" alt="<?= esc($g['nama_wisata'] ?? 'Galeri Wisata') ?>">
                                <div class="gallery-content">
                                    <div class="gallery-info">
                                        <h5 class="text-white text-uppercase mb-2 text-truncate" style="max-width: 150px;"><?= esc($g['nama_wisata'] ?? 'Galeri Wisata') ?></h5>
                                        <a href="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" data-lightbox="gallery-all" class="btn-hover text-white">Perbesar <i class="fa fa-arrow-right ms-2"></i></a>
                                    </div>
                                </div>
                                <div class="gallery-plus-icon">
                                    <a href="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" data-lightbox="gallery-all" class="my-auto"><i class="fas fa-plus fa-2x text-white"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($semuaGaleri)): ?>
                        <div class="col-12 text-muted">Belum ada galeri.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tab per Kategori -->
            <?php foreach ($kategori as $k): ?>
                <div id="GalleryTab-<?= $k['id'] ?>" class="tab-pane fade show p-0">
                    <div class="row g-2 justify-content-center">
                        <?php
                        $count = 0;
                        foreach ($semuaGaleri as $g):
                            if ($g['kategori_id'] == $k['id']) {
                                $count++;
                                if ($count > 3) continue; // Maksimal 3 foto per kategori
                        ?>
                                <div class="col-sm-6 col-md-4 col-lg-4">
                                    <div class="gallery-item h-100">
                                        <img src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" class="img-fluid w-100 h-100 rounded skeleton-effect" style="object-fit: cover; min-height: 250px;" loading="lazy" onload="this.classList.remove('skeleton-effect')" alt="<?= esc($g['nama_wisata'] ?? 'Galeri Wisata') ?>">
                                        <div class="gallery-content">
                                            <div class="gallery-info">
                                                <h5 class="text-white text-uppercase mb-2 text-truncate" style="max-width: 200px;"><?= esc($g['nama_wisata'] ?? 'Galeri Wisata') ?></h5>
                                                <a href="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" data-lightbox="gallery-<?= $k['id'] ?>" class="btn-hover text-white">Perbesar <i class="fa fa-arrow-right ms-2"></i></a>
                                            </div>
                                        </div>
                                        <div class="gallery-plus-icon">
                                            <a href="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" data-lightbox="gallery-<?= $k['id'] ?>" class="my-auto"><i class="fas fa-plus fa-2x text-white"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        endforeach;
                        if ($count == 0):
                            ?>
                            <div class="col-12 text-muted">Belum ada galeri untuk kategori ini.</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Gallery End -->

<!-- Pengelola Sistem Start -->
<div class="container-fluid guide py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Tim Pengelola</h5>
            <h1 class="mb-0">Kenali Author Kami</h1>
        </div>
        <div class="row g-4 justify-content-center">
            <?php if (!empty($admins)): ?>
                <?php foreach ($admins as $idx => $admin): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="guide-item">
                            <div class="guide-img">
                                <div class="guide-img-efects">
                                    <?php
                                    $avatar = base_url('assets/img/guide-' . (($idx % 4) + 1) . '.jpg');
                                    if (!empty($admin['foto_profil']) && file_exists(FCPATH . 'uploads/profil/' . $admin['foto_profil'])) {
                                        $avatar = base_url('uploads/profil/' . $admin['foto_profil']);
                                    }
                                    ?>
                                    <img src="<?= $avatar ?>" class="img-fluid w-100 rounded-top skeleton-effect" loading="lazy" onload="this.classList.remove('skeleton-effect')" style="object-fit:cover; height:300px;" alt="<?= esc($admin['nama']) ?>">
                                </div>
                                <div class="guide-icon rounded-pill p-2">
                                    <?php if (!empty($admin['whatsapp'])): ?>
                                        <?php
                                        // Bersihkan karakter selain angka
                                        $wa = preg_replace('/[^0-9]/', '', $admin['whatsapp']);
                                        // Pastikan berawalan 62
                                        if (substr($wa, 0, 1) === '0') $wa = '62' . substr($wa, 1);
                                        ?>
                                        <a class="btn btn-square btn-primary rounded-circle mx-1" href="https://wa.me/<?= $wa ?>" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($admin['instagram'])): ?>
                                        <?php $ig = ltrim(trim($admin['instagram']), '@'); ?>
                                        <a class="btn btn-square btn-primary rounded-circle mx-1" href="https://instagram.com/<?= esc($ig) ?>" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($admin['email'])): ?>
                                        <a class="btn btn-square btn-primary rounded-circle mx-1" href="mailto:<?= esc($admin['email']) ?>" title="Email"><i class="fas fa-envelope"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="guide-title text-center rounded-bottom p-4">
                                <div class="guide-title-inner">
                                    <h4 class="mt-3 fs-5"><?= esc($admin['nama']) ?></h4>
                                    <p class="mb-0 text-muted">Administrator</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted">
                    <p>Belum ada data administrator.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Pengelola Sistem End -->

<?= $this->include('pengunjung/layout/footer') ?>
