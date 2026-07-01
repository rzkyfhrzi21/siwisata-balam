<?= $this->include('pengunjung/layout/header') ?>

<style>
    .btn-jelajah {
        background-color: #13357B;
        color: #ffffff !important;
        border: 1px solid #13357B;
        transition: all 0.3s;
    }

    .btn-jelajah:hover {
        background-color: #ffffff;
        color: #13357B !important;
    }
</style>
<!-- Header Start -->
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
<!-- Header End -->

<!-- Single Destination Detail Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Kolom kiri: foto utama + galeri -->
            <div class="col-lg-7">
                <!-- Foto Utama -->
                <?php if ($wisata['thumbnail']): ?>
                    <div class="destination-img mb-4" style="height: 420px; overflow: hidden; border-radius: 10px;">
                        <img src="<?= base_url('uploads/thumbnail/' . $wisata['thumbnail']) ?>"
                            class="img-fluid w-100 h-100 rounded skeleton-effect"
                            style="object-fit: cover;"
                            loading="lazy" onload="this.classList.remove('skeleton-effect')"
                            alt="<?= esc($wisata['nama_wisata']) ?>">
                    </div>
                <?php endif; ?>

                <!-- Galeri Foto (Lightbox) -->
                <?php if (!empty($galeri)): ?>
                    <div class="mx-auto mb-3">
                        <h5 class="section-title px-3">Galeri</h5>
                        <h4 class="mb-3">Foto & Dokumentasi</h4>
                    </div>
                    <div class="row g-2">
                        <?php foreach ($galeri as $g): ?>
                            <?php if ($g['tipe_file'] === 'foto'): ?>
                                <div class="col-6 col-md-4">
                                    <div class="gallery-item" style="height: 180px; overflow: hidden; border-radius: 8px; position: relative;">
                                        <img src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>"
                                            class="img-fluid w-100 h-100 rounded skeleton-effect"
                                            style="object-fit: cover;"
                                            loading="lazy" onload="this.classList.remove('skeleton-effect')"
                                            alt="<?= esc($wisata['nama_wisata']) ?>">
                                        <div class="gallery-plus-icon">
                                            <a href="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>"
                                                data-lightbox="galeri-<?= $wisata['id'] ?>"
                                                data-title="<?= esc($wisata['nama_wisata']) ?>"
                                                class="my-auto">
                                                <i class="fas fa-plus fa-2x text-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif ($g['tipe_file'] === 'video'): ?>
                                <div class="col-12">
                                    <video controls class="w-100 rounded mb-2">
                                        <source src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>">
                                        Browser Anda tidak mendukung pemutar video.
                                    </video>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Kolom kanan: info wisata -->
            <div class="col-lg-5">
                <!-- Badge Kategori -->
                <?php if ($kategori): ?>
                    <span class="badge bg-primary rounded-pill px-3 py-2 mb-2">
                        <?= esc($kategori['nama_kategori']) ?>
                    </span>
                <?php endif; ?>

                <h2 class="mb-3"><?= esc($wisata['nama_wisata']) ?></h2>

                <!-- Deskripsi -->
                <p class="mb-4"><?= nl2br(esc($wisata['deskripsi'])) ?></p>

                <!-- Info Detail -->
                <ul class="list-unstyled mb-4">
                    <li class="mb-2">
                        <i class="fa fa-map-marker-alt text-primary me-2"></i>
                        <strong>Alamat:</strong> <?= esc($wisata['alamat']) ?>
                    </li>
                    <li class="mb-2">
                        <i class="fa fa-clock text-primary me-2"></i>
                        <strong>Jam Operasional:</strong> <?= esc($wisata['jam_operasional']) ?>
                    </li>
                    <li class="mb-2">
                        <i class="fa fa-calendar-alt text-primary me-2"></i>
                        <strong>Hari Operasional:</strong> <?= esc($wisata['hari_operasional']) ?>
                    </li>
                    <li class="mb-2">
                        <i class="fa fa-ticket-alt text-primary me-2"></i>
                        <strong>Tiket / Harga:</strong> <?= esc($wisata['harga_tiket']) ?>
                    </li>
                </ul>

                <!-- Fasilitas (tampilkan sebagai badge) -->
                <?php if (!empty($fasilitas_list)): ?>
                    <div class="mb-4">
                        <h6><i class="fa fa-concierge-bell text-primary me-2"></i>Fasilitas</h6>
                        <?php foreach ($fasilitas_list as $fas): ?>
                            <span class="badge bg-light text-dark border me-1 mb-1 py-2 px-3">
                                <?php if (!empty($fas['icon'])): ?>
                                    <i class="<?= esc($fas['icon']) ?> text-primary me-1"></i>
                                <?php else: ?>
                                    <i class="fa fa-check text-primary me-1"></i>
                                <?php endif; ?>
                                <?= esc($fas['nama_fasilitas']) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Aturan/Tata Tertib -->
                <?php if (!empty($wisata['aturan'])): ?>
                    <div class="mb-4">
                        <h6><i class="fa fa-info-circle text-primary me-2"></i>Aturan / Tata Tertib</h6>
                        <p class="text-muted"><?= nl2br(esc($wisata['aturan'])) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Tombol Pesan Tiket via WA -->
                <?php if ($kontak && !empty($kontak['has_ticket_feature']) && !empty($kontak['nomor_whatsapp'])): ?>
                    <?php
                    $nomorWA  = preg_replace('/[^0-9]/', '', $kontak['nomor_whatsapp']);
                    $pesan    = urlencode("Halo kak, saya ingin bertanya/memesan tiket untuk {$wisata['nama_wisata']}. Mohon informasinya ya 🙏");
                    $linkWA   = "https://wa.me/{$nomorWA}?text={$pesan}";
                    ?>
                    <a href="<?= $linkWA ?>" target="_blank" rel="noopener"
                        class="btn btn-success rounded-pill py-3 px-5 mt-2 w-100">
                        <i class="fab fa-whatsapp me-2"></i>Pesan Tiket via WhatsApp
                    </a>
                <?php else: ?>
                    <button class="btn btn-secondary rounded-pill py-3 px-3 mt-2 w-100" style="cursor: not-allowed; opacity: 0.8;" disabled>
                        <i class="fa fa-info-circle me-1"></i>Pesan tiket online tidak tersedia, silakan langsung menuju lokasi.
                    </button>
                <?php endif; ?>

                <a href="<?= base_url('destinasi') ?>" class="btn btn-outline-primary rounded-pill py-2 px-4 mt-3 w-100">
                    <i class="fa fa-arrow-left me-2"></i>Kembali ke Daftar Wisata
                </a>
            </div>
        </div>

        <!-- Peta Mini Leaflet (jika ada koordinat) -->
        <?php if (!empty($wisata['latitude']) && !empty($wisata['longitude'])): ?>
            <div class="row mt-5">
                <div class="col-12">
                    <h5 class="section-title px-3">Lokasi</h5>
                    <h4 class="mb-3">Peta Lokasi <?= esc($wisata['nama_wisata']) ?></h4>
                    <div id="map-detail" style="height: 400px; border-radius: 10px; z-index: 0;"></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- Single Destination Detail End -->

<?php if (!empty($wisata['latitude']) && !empty($wisata['longitude'])): ?>
    <!-- Leaflet CSS & JS untuk peta mini -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var lat = <?= (float)$wisata['latitude'] ?>;
        var lon = <?= (float)$wisata['longitude'] ?>;
        var namaWisata = "<?= esc($wisata['nama_wisata'], 'js') ?>";
        var linkGmaps = "<?= esc($wisata['link_gmaps'] ?? '', 'js') ?>";

        var map = L.map('map-detail').setView([lat, lon], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(map);

        var googleMapsUrl = linkGmaps ? linkGmaps : 'https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lon;
        var popupContent = '<div class="text-center">' +
            '<b class="mb-2 d-block">' + namaWisata + '</b>' +
            '<a href="' + googleMapsUrl + '" target="_blank" class="btn btn-sm rounded-pill w-100 btn-jelajah" style="font-size: 12px;"><i class="fa fa-directions me-1"></i>Menuju Lokasi</a>' +
            '</div>';

        L.marker([lat, lon])
            .addTo(map)
            .bindPopup(popupContent)
            .openPopup();
    </script>
<?php endif; ?>

<?= $this->include('pengunjung/layout/footer') ?>
