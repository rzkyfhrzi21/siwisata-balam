<?php
/* ======================================================
   VIEW LAYANAN (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna/pengunjung web.
   
   Halaman ini adalah halaman statis yang menampilkan daftar fitur atau
   layanan unggulan apa saja yang ada di aplikasi SiWisata Balam.
====================================================== */
?>
<?= $this->include('pengunjung/layout/header') ?>

    <!-- ======================================================
         BAGIAN HEADER HALAMAN (BREADCRUMB)
         
         Ini adalah area judul halaman besar di bagian atas,
         biasanya dilengkapi dengan jalur navigasi (breadcrumb)
         seperti "Home > Pages > Layanan Kami".
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
     BAGIAN DAFTAR LAYANAN (SERVICES)
     
     Ini adalah area utama halaman yang memuat kotak-kotak (grid) informasi
     berisi penjelasan mengenai fitur unggulan aplikasi ini 
     (misal: Peta WebGIS, Rekomendasi Terdekat, Galeri, dll).
====================================================== -->
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
<?= $this->include('pengunjung/layout/footer') ?>
