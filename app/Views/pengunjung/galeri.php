<?php
/* ======================================================
   VIEW GALERI MEDIA (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna/pengunjung web.
   
   Halaman ini menampilkan seluruh koleksi foto dan video wisata.
   Koleksi tersebut bisa difilter (disaring) berdasarkan kategori wisata,
   dan jika diklik, media akan membesar menggunakan fitur Modal (Slideshow/Carousel).
====================================================== */
?>
<?= $this->include('pengunjung/layout/header') ?>
<style>
    /* Skeleton Loading Effect */
    .skeleton-effect {
        background-color: #e2e5e7;
        background-image: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0));
        background-size: 200px 100%;
        background-repeat: no-repeat;
        animation: shimmer 1.5s infinite linear;
        color: transparent;
    }

    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }

        100% {
            background-position: calc(200px + 100%) 0;
        }
    }
</style>
<!-- ======================================================
     BAGIAN HEADER HALAMAN (BREADCRUMB)
     
     Ini adalah area judul halaman besar di bagian atas,
     biasanya dilengkapi dengan jalur navigasi (breadcrumb)
     seperti "Home > Pages > Galeri Wisata".
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
     BAGIAN DAFTAR MEDIA GALERI & FILTER KATEGORI
     
     Ini adalah area utama halaman.
     Di bagian atas terdapat tombol-tombol filter (Semua, Pantai, Gunung, dll).
     
     Penjelasan Looping (Filter):
     - `foreach ($kategori as $k)` : Sistem mencetak nama kategori
       dari database ke bentuk tombol tab.
====================================================== -->
<div class="container-fluid gallery py-5 my-5">
    <div class="mx-auto text-center mb-5" style="max-width: 900px;">
        <h5 class="section-title px-3">Galeri Wisata</h5>
        <h1 class="mb-4">Dokumentasi Destinasi Terbaik.</h1>
        <p class="mb-0">
            Jelajahi keindahan berbagai destinasi wisata di Kota Bandar Lampung melalui koleksi foto dan video kami.
            Setiap destinasi menampilkan momen-momen terbaik yang menunggu untuk Anda kunjungi.
        </p>
    </div>

    <div class="tab-class text-center">
        <ul class="nav nav-pills d-inline-flex justify-content-center mb-5">
            <li class="nav-item">
                <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill active" data-bs-toggle="pill" data-bs-target="#Semua" role="button" style="cursor: pointer;">
                    <span class="text-dark" style="width: 150px;">Semua</span>
                </a>
            </li>
            <?php foreach ($kategori as $k): ?>
                <li class="nav-item">
                    <a class="d-flex py-2 mx-3 border border-primary bg-light rounded-pill" data-bs-toggle="pill" data-bs-target="#<?= $k['slug'] ?>" role="button" style="cursor: pointer;">
                        <span class="text-dark" style="width: 150px;"><?= esc($k['nama_kategori']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content">
            <?php
            $classes = ['col-xl-2', 'col-xl-3', 'col-xl-3', 'col-xl-2', 'col-xl-2'];
            ?>
            <!-- ======================================================
                 ISI TAB "SEMUA" (LOOPING GALERI ALL)
                 
                 Bagian ini menampilkan SELURUH media (foto/video) tanpa difilter.
                 
                 Penjelasan Looping:
                 - `foreach ($galeriAll as $g)` : Mencetak kotak media satu per satu.
                 
                 Kolom DB yang digunakan:
                 - $g['tipe_file']      : 'foto' atau 'video' (Menentukan tag <img> atau <video>).
                 - $g['nama_file']      : Nama file asli. Sistem akan mengambil
                                          filenya dari folder `uploads/galeri/`.
                 - $g['nama_wisata']    : Judul destinasi.
                 - $g['destinasi_slug'] : Link URL wisata untuk tombol "Lihat Destinasi".
            ====================================================== -->
            <div id="Semua" class="tab-pane fade show p-0 active">
                <div class="row g-2">
                    <?php if (!empty($galeriAll)): ?>
                        <?php $i = 0;
                        foreach ($galeriAll as $g): ?>
                            <?php $colClass = $classes[$i % count($classes)]; ?>
                            <div class="col-sm-6 col-md-6 col-lg-4 <?= $colClass ?>">
                                <div class="gallery-item h-100">
                                    <?php if ($g['tipe_file'] == 'video'): ?>
                                        <video src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" class="img-fluid w-100 h-100 rounded skeleton-effect" style="object-fit:cover;" muted loop onmouseover="this.play()" onmouseout="this.pause()" preload="metadata" onloadedmetadata="this.classList.remove('skeleton-effect')"></video>
                                    <?php else: ?>
                                        <img src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" class="img-fluid w-100 h-100 rounded skeleton-effect" alt="<?= esc($g['nama_wisata']) ?>" style="object-fit:cover;" loading="lazy" onload="this.classList.remove('skeleton-effect')">
                                    <?php endif; ?>

                                    <div class="gallery-content">
                                        <div class="gallery-info">
                                            <h5 class="text-white text-uppercase mb-2"><?= esc($g['nama_wisata']) ?></h5>
                                            <a href="<?= base_url('wisata/' . $g['destinasi_slug']) ?>" class="btn-hover text-white">Lihat Destinasi <i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                    </div>
                                    <div class="gallery-plus-icon">
                                        <a href="javascript:void(0);" onclick="openCarousel('Semua', <?= $i ?>)" class="my-auto"><i class="fas fa-<?= $g['tipe_file'] == 'video' ? 'play' : 'plus' ?> fa-2x text-white"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php $i++;
                        endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 py-5 text-muted">
                            <i class="fa fa-image fa-3x mb-3"></i>
                            <p>Belum ada media galeri yang tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ======================================================
                 ISI TAB "PER KATEGORI" (LOOPING BERSARANG)
                 
                 Bagian ini berisi loop dua tingkat (Bersarang):
                 1. `foreach ($kategori as $k)` : Membuat wadah per kategori.
                 2. `foreach ($galeriPerKategori[$k['id']] as $g)` : Mencetak kotak
                    foto/video khusus untuk kategori tersebut saja.
            ====================================================== -->
            <?php foreach ($kategori as $k): ?>
                <div id="<?= $k['slug'] ?>" class="tab-pane fade show p-0">
                    <div class="row g-2">
                        <?php if (!empty($galeriPerKategori[$k['id']])): ?>
                            <?php $i = 0;
                            foreach ($galeriPerKategori[$k['id']] as $g): ?>
                                <?php $colClass = $classes[$i % count($classes)]; ?>
                                <div class="col-sm-6 col-md-6 col-lg-4 <?= $colClass ?>">
                                    <div class="gallery-item h-100">
                                        <?php if ($g['tipe_file'] == 'video'): ?>
                                            <video src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" class="img-fluid w-100 h-100 rounded skeleton-effect" style="object-fit:cover;" muted loop onmouseover="this.play()" onmouseout="this.pause()" preload="metadata" onloadedmetadata="this.classList.remove('skeleton-effect')"></video>
                                        <?php else: ?>
                                            <img src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" class="img-fluid w-100 h-100 rounded skeleton-effect" alt="<?= esc($g['nama_wisata']) ?>" style="object-fit:cover;" loading="lazy" onload="this.classList.remove('skeleton-effect')">
                                        <?php endif; ?>

                                        <div class="gallery-content">
                                            <div class="gallery-info">
                                                <h5 class="text-white text-uppercase mb-2"><?= esc($g['nama_wisata']) ?></h5>
                                                <a href="<?= base_url('wisata/' . $g['destinasi_slug']) ?>" class="btn-hover text-white">Lihat Destinasi <i class="fa fa-arrow-right ms-2"></i></a>
                                            </div>
                                        </div>
                                        <div class="gallery-plus-icon">
                                            <a href="javascript:void(0);" onclick="openCarousel('<?= $k['id'] ?>', <?= $i ?>)" class="my-auto"><i class="fas fa-<?= $g['tipe_file'] == 'video' ? 'play' : 'plus' ?> fa-2x text-white"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?php $i++;
                            endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 py-5 text-muted">
                                <i class="fa fa-image fa-3x mb-3"></i>
                                <p>Belum ada media galeri untuk kategori ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>
<!-- ======================================================
     MODAL SLIDESHOW GALLERY (SEMUA)
     
     Ini adalah formulir popup (jendela mengambang) tak kasat mata
     yang baru akan muncul jika gambar di tab "Semua" diklik.
     Di dalamnya terdapat fitur carousel (geser kiri/kanan).
====================================================== -->
<div class="modal fade" id="modalGallerySemua" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0 justify-content-end" style="position: absolute; right: 0; top: -40px; z-index: 1055;">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <div id="carouselGallerySemua" class="carousel slide" data-bs-interval="false">
                    <div class="carousel-inner">
                        <?php $index = 0;
                        foreach ($galeriAll as $g): ?>
                            <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                                <?php if ($g['tipe_file'] == 'video'): ?>
                                    <video src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" class="img-fluid rounded shadow-lg" style="max-height: 85vh; object-fit: contain; width: 100%; background: rgba(0,0,0,0.8);" controls></video>
                                <?php else: ?>
                                    <img src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" class="img-fluid rounded shadow-lg" style="max-height: 85vh; object-fit: contain; width: 100%; background: rgba(0,0,0,0.8);" alt="<?= esc($g['nama_wisata']) ?>">
                                <?php endif; ?>
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); border-radius: 10px; padding: 10px;">
                                    <h5 class="text-white mb-0"><?= esc($g['nama_wisata']) ?></h5>
                                </div>
                            </div>
                        <?php $index++;
                        endforeach; ?>
                    </div>
                    <?php if (count($galeriAll) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselGallerySemua" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 3rem; height: 3rem;"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselGallerySemua" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true" style="width: 3rem; height: 3rem;"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================
     MODAL SLIDESHOW GALLERY (PER KATEGORI)
     
     Sama seperti modal di atas, tetapi memisahkan galeri 
     hanya untuk gambar/video di kategori yang sedang dipilih.
====================================================== -->
<?php foreach ($kategori as $k): ?>
    <?php if (!empty($galeriPerKategori[$k['id']])): ?>
        <div class="modal fade" id="modalGallery<?= $k['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-header border-0 pb-0 justify-content-end" style="position: absolute; right: 0; top: -40px; z-index: 1055;">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <div id="carouselGallery<?= $k['id'] ?>" class="carousel slide" data-bs-interval="false">
                            <div class="carousel-inner">
                                <?php $index = 0;
                                foreach ($galeriPerKategori[$k['id']] as $g): ?>
                                    <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                                        <?php if ($g['tipe_file'] == 'video'): ?>
                                            <video src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" class="img-fluid rounded shadow-lg" style="max-height: 85vh; object-fit: contain; width: 100%; background: rgba(0,0,0,0.8);" controls></video>
                                        <?php else: ?>
                                            <img src="<?= base_url('uploads/galeri/' . $g['nama_file']) ?>" class="img-fluid rounded shadow-lg" style="max-height: 85vh; object-fit: contain; width: 100%; background: rgba(0,0,0,0.8);" alt="<?= esc($g['nama_wisata']) ?>">
                                        <?php endif; ?>
                                        <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); border-radius: 10px; padding: 10px;">
                                            <h5 class="text-white mb-0"><?= esc($g['nama_wisata']) ?></h5>
                                        </div>
                                    </div>
                                <?php $index++;
                                endforeach; ?>
                            </div>
                            <?php if (count($galeriPerKategori[$k['id']]) > 1): ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselGallery<?= $k['id'] ?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 3rem; height: 3rem;"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselGallery<?= $k['id'] ?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="width: 3rem; height: 3rem;"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<!-- ======================================================
     SKRIP JAVASCRIPT (KONTROL GALERI & VIDEO)
     
     Alur Kerjanya:
     (1) Saat pengunjung mengklik icon tambah/play di gambar (openCarousel):
         - Matikan (pause) semua video yang lagi main di background agar suara tidak tabrakan.
         - Munculkan Modal popup raksasa.
         - Geser (slide) otomatis ke foto/video urutan yang diklik.
     (2) Saat modal popup ditutup (hidden.bs.modal):
         - Matikan (pause) video yang sedang diputar di modal.
     (3) Saat tab kategori dipencet:
         - Diam-diam ubah URL di atas browser (contoh: jadi #pantai)
         - Tujuannya agar jika web di-refresh, tab tidak pindah ke awal.
====================================================== -->
<script>
    // (1) Fungsi membuka modal
    function openCarousel(modalIdSuffix, index) {
        const modalId = '#modalGallery' + modalIdSuffix;
        const carouselId = '#carouselGallery' + modalIdSuffix;

        // Hentikan semua video yang mungkin sedang berjalan di latar belakang
        document.querySelectorAll('video').forEach(vid => {
            if (!vid.hasAttribute('muted')) vid.pause(); // Jangan pause thumbnail video
        });

        // Buka modal
        const myModal = new bootstrap.Modal(document.querySelector(modalId));
        myModal.show();

        // Geser carousel ke index foto yang di-klik
        const carousel = bootstrap.Carousel.getOrCreateInstance(document.querySelector(carouselId));
        carousel.to(index);
    }

    // Matikan video saat modal ditutup
    document.addEventListener('hidden.bs.modal', function(event) {
        if (event.target.id.startsWith('modalGallery')) {
            event.target.querySelectorAll('video').forEach(vid => vid.pause());
        }
    });

    // Matikan video saat slide berpindah
    document.addEventListener('slide.bs.carousel', function(event) {
        if (event.target.id.startsWith('carouselGallery')) {
            event.target.querySelectorAll('video').forEach(vid => vid.pause());
        }
    });

    // Update URL hash saat tab berubah tanpa efek lompatan (jump)
    document.querySelectorAll('a[data-bs-toggle="pill"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(event) {
            const hash = event.target.getAttribute('data-bs-target');
            if(history.pushState) {
                history.pushState(null, null, hash);
            } else {
                window.location.hash = hash;
            }
        });
    });

    // Buka tab secara otomatis jika URL memiliki hash (misal: /galeri#pantai)
    window.addEventListener('DOMContentLoaded', (event) => {
        const hash = window.location.hash;
        if (hash) {
            const activeTab = document.querySelector(`a[data-bs-target="${hash}"]`);
            if (activeTab) {
                new bootstrap.Tab(activeTab).show();
            }
        }
    });
</script>

<?= $this->include('pengunjung/layout/footer') ?>
