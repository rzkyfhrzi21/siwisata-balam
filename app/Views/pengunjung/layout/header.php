<?php
/* ======================================================
   VIEW LAYOUT HEADER (MVC - VIEW)
   
   Apa itu View Layout?
   Ini adalah potongan kodingan (Template) yang digunakan 
   di SEMUA halaman pengunjung (Beranda, Kontak, dll).
   Berisi elemen dasar seperti tag <head>, pemanggilan CSS, 
   dan menu navigasi atas (Navbar).
====================================================== */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <?php
    $pageTitle = 'Beranda';
    if (isset($active)) {
        switch ($active) {
            case 'tentang': $pageTitle = 'Tentang Kami'; break;
            case 'destinasi': $pageTitle = 'Destinasi Wisata'; break;
            case 'peta': $pageTitle = 'Peta WebGIS'; break;
            case 'rekomendasi': $pageTitle = 'Rekomendasi Terdekat'; break;
            case 'galeri': $pageTitle = 'Galeri Wisata'; break;
            case 'layanan': $pageTitle = 'Layanan Pariwisata'; break;
            case 'kontak': $pageTitle = 'Hubungi Kami'; break;
        }
    }
    if (isset($destinasi) && isset($destinasi['nama_wisata'])) {
        $pageTitle = esc($destinasi['nama_wisata']);
    }
    ?>
    <title><?= $pageTitle ?> | SiWisata Balam</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="SiWisata Balam - Sistem Informasi Destinasi Wisata Kota Bandar Lampung. Temukan rekomendasi, peta interaktif, harga tiket, dan galeri wisata alam, budaya, dan edukasi terbaik di Bandar Lampung.">
    <meta name="keywords" content="wisata bandar lampung, destinasi bandar lampung, pariwisata lampung, peta wisata lampung, siwisata balam, rekomendasi wisata, pantai lampung, taman lampung">
    <meta name="author" content="SiWisata Balam">
    <meta name="robots" content="index, follow">

    <link rel="icon" href="<?= base_url('logo.svg') ?>" type="image/svg+xml">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?= base_url('assets/lib/owlcarousel/assets/owl.carousel.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/lib/lightbox/css/lightbox.min.css') ?>" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>

<body>

    <!-- ======================================================
         BAGIAN LOADING SPINNER
         Animasi putar yang muncul sebentar saat halaman baru dimuat.
    ====================================================== -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- ======================================================
         BAGIAN NAVIGASI ATAS (NAVBAR)
         Ini adalah menu utama (Beranda, Tentang, Destinasi, dll).
    ====================================================== -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
            <a href="<?= base_url('/') ?>" class="navbar-brand p-0">
                <h1 class="m-0 d-flex align-items-center">
                    <img src="<?= base_url('logo.svg') ?>" alt="Logo" class="me-3" style="width: 45px; height: auto;">
                    SiWisata Balam
                </h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="<?= base_url('/') ?>" class="nav-item nav-link <?= (isset($active) && $active == 'home') ? 'active' : '' ?>">Beranda</a>
                    <a href="<?= base_url('tentang') ?>" class="nav-item nav-link <?= (isset($active) && $active == 'tentang') ? 'active' : '' ?>">Tentang</a>
                    <a href="<?= base_url('destinasi') ?>" class="nav-item nav-link <?= (isset($active) && $active == 'destinasi') ? 'active' : '' ?>">Destinasi</a>
                    <a href="<?= base_url('peta') ?>" class="nav-item nav-link <?= (isset($active) && $active == 'peta') ? 'active' : '' ?>">Peta</a>
                    <a href="<?= base_url('rekomendasi') ?>" class="nav-item nav-link <?= (isset($active) && $active == 'rekomendasi') ? 'active' : '' ?>">Terdekat</a>
                    <a href="<?= base_url('galeri') ?>" class="nav-item nav-link <?= (isset($active) && $active == 'galeri') ? 'active' : '' ?>">Galeri</a>
                    <a href="<?= base_url('kontak') ?>" class="nav-item nav-link <?= (isset($active) && $active == 'kontak') ? 'active' : '' ?>">Kontak</a>
                </div>
                <a href="<?= base_url('destinasi') ?>" class="btn btn-primary rounded-pill py-2 px-4 ms-lg-4">Jelajahi</a>
            </div>
        </nav>


        <?php if (isset($active) && $active == 'home'): ?>
            <!-- ======================================================
                 BAGIAN BANNER SLIDESHOW (HERO CAROUSEL)
                 Hanya muncul di halaman utama (Beranda/Home).
                 Berisi gambar-gambar besar yang bisa bergeser.
            ====================================================== -->
            <div class="carousel-header">
                <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active"></li>
                        <li data-bs-target="#carouselId" data-bs-slide-to="1"></li>
                        <li data-bs-target="#carouselId" data-bs-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <img src="<?= base_url('assets/img/carousel-2.jpg') ?>" class="img-fluid" alt="Image">
                            <div class="carousel-caption">
                                <div class="p-3" style="max-width: 900px;">
                                    <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 3px;">Jelajahi Bandar Lampung</h4>
                                    <h1 class="display-2 text-capitalize text-white mb-4">Temukan Keindahan Tapis Berseri!</h1>
                                    <p class="mb-5 fs-5">Selamat datang di SiWisata Balam. Platform direktori pariwisata terpadu untuk menemukan surga tersembunyi, wisata alam, buatan, hingga pesona kuliner di Kota Bandar Lampung.
                                    </p>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a class="btn-hover-bg btn btn-primary rounded-pill text-white py-3 px-5" href="<?= base_url('destinasi') ?>">Mulai Menjelajah</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="<?= base_url('assets/img/carousel-1.jpg') ?>" class="img-fluid" alt="Image">
                            <div class="carousel-caption">
                                <div class="p-3" style="max-width: 900px;">
                                    <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 3px;">Peta Pariwisata Cerdas</h4>
                                    <h1 class="display-2 text-capitalize text-white mb-4">Rekomendasi Wisata Terdekat</h1>
                                    <p class="mb-5 fs-5">Gunakan fitur WebGIS interaktif dan Algoritma Haversine untuk mendapatkan rekomendasi destinasi wisata terbaik yang paling dekat dengan lokasi Anda saat ini.
                                    </p>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a class="btn-hover-bg btn btn-primary rounded-pill text-white py-3 px-5" href="<?= base_url('rekomendasi') ?>">Cari Wisata Terdekat</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="<?= base_url('assets/img/carousel-3.jpg') ?>" class="img-fluid" alt="Image">
                            <div class="carousel-caption">
                                <div class="p-3" style="max-width: 900px;">
                                    <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 3px;">Rencanakan Liburan Anda</h4>
                                    <h1 class="display-2 text-capitalize text-white mb-4">Kemudahan Informasi & Pemesanan</h1>
                                    <p class="mb-5 fs-5">Dapatkan akses langsung ke galeri media kualitas tinggi, informasi harga tiket transparan, serta rute Google Maps untuk memastikan liburan Anda berjalan sempurna.
                                    </p>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a class="btn-hover-bg btn btn-primary rounded-pill text-white py-3 px-5" href="<?= base_url('galeri') ?>">Lihat Galeri</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon btn bg-primary" aria-hidden="false"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                        <span class="carousel-control-next-icon btn bg-primary" aria-hidden="false"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            </div>
            
            <!-- ======================================================
                 BAGIAN FORM PENCARIAN CEPAT
                 Kolom pencarian yang melayang di atas banner halaman Home.
                 Jika pengunjung mengetik dan enter, akan dikirim ke halaman /destinasi
                 dengan metode GET.
                 Kolom Input:
                 - name="search" : Kata kunci yang diketik.
            ====================================================== -->
    </div>
    <div class="container-fluid search-bar position-relative" style="top: -50%; transform: translateY(-50%);">
        <div class="container">
            <form action="<?= base_url('destinasi') ?>" method="GET" class="position-relative rounded-pill w-100 mx-auto p-5" style="background: rgba(19, 53, 123, 0.8);">
                <input class="form-control border-0 rounded-pill w-100 py-3 ps-4 pe-5" type="text" name="search" placeholder="Mau liburan kemana? (Cari nama wisata...)">
                <input type="hidden" name="kategori" value="">
                <button type="submit" class="btn btn-primary rounded-pill py-2 px-4 position-absolute me-2" style="top: 50%; right: 46px; transform: translateY(-50%);">Cari Sekarang</button>
            </form>
        </div>
    </div>
    </div>
<?php else: ?>
    </div>
    </div>
<?php endif; ?>
