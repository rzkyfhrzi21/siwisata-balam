<?php
/* ======================================================
   VIEW TENTANG KAMI (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna/pengunjung web.
   
   Halaman ini adalah halaman profil atau pengenalan tentang 
   Sistem Informasi Pariwisata Kota Bandar Lampung, 
   termasuk menampilkan daftar tim pengelola/admin.
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
     seperti "Home > Pages > Tentang Kami".
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
     BAGIAN TENTANG KAMI (ABOUT US)
     
     Menampilkan gambar logo/ilustrasi di sebelah kiri dan 
     teks penjelasan tentang aplikasi SiWisata Balam di sebelah kanan
     beserta poin-poin keunggulan sistem.
====================================================== -->
<div class="container-fluid about py-5">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="h-100 p-5 d-flex align-items-center justify-content-center">
                    <img src="<?= base_url('logo.svg') ?>" class="img-fluid w-100 skeleton-effect" style="object-fit: contain; max-height: 400px;" alt="Tentang SiWisata Balam" loading="lazy" onload="this.classList.remove('skeleton-effect')">
                </div>
            </div>
            <div class="col-lg-7" style="background: linear-gradient(rgba(255, 255, 255, .9), rgba(255, 255, 255, .9)), url(<?= base_url('logo.svg') ?>) center center no-repeat; background-size: cover;">
                <h5 class="section-about-title pe-3">Tentang Kami</h5>
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
            </div>
        </div>
    </div>
</div>
<!-- ======================================================
     BAGIAN TIM PENGELOLA (ADMIN STAFF)
     
     Menampilkan daftar foto dan nama para pengelola (Admin)
     yang terdaftar di database. Diambil dari Controller yang mengirimkan
     variabel $admins (bersumber dari tabel 'admin').

     Penjelasan Looping (Perulangan):
     - Menggunakan perintah `foreach ($admins as $admin)` yang artinya:
       Sistem akan memanggil data admin satu per satu dari database. 
       Untuk setiap 1 admin, sistem otomatis membuatkan 1 buah kartu profil.
       
     Data/Kolom Database yang Digunakan:
     - $admin['nama']        : Menampilkan teks nama lengkap pengelola.
     - $admin['foto_profil'] : Menampilkan foto wajah. Sistem mengecek file
                               fisiknya di dalam folder server `uploads/profil/`.
                               Jika admin belum pernah upload foto (kosong), 
                               akan menggunakan foto bawaan template `assets/img/guide-1.jpg`.
     - $admin['whatsapp']    : Nomor WA untuk tombol link chat WhatsApp.
     - $admin['instagram']   : Username IG untuk tombol link profil Instagram.
     - $admin['email']       : Alamat email untuk tombol kirim pesan Email.
====================================================== -->
<div class="container-fluid guide py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Tim Pengelola</h5>
            <h1 class="mb-0">Kenali Author Kami</h1>
        </div>
        <div class="row g-4 justify-content-center">
            <?php if (!empty($admins)): ?>
                <?php foreach ($admins as $admin): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="guide-item">
                            <div class="guide-img">
                                <div class="guide-img-efects">
                                    <?php
                                    // Fallback to template's guide image if no foto_profil
                                    $foto = !empty($admin['foto_profil']) && file_exists(FCPATH . 'uploads/profil/' . $admin['foto_profil'])
                                        ? base_url('uploads/profil/' . $admin['foto_profil'])
                                        : base_url('assets/img/guide-1.jpg');
                                    ?>
                                    <img src="<?= $foto ?>" class="img-fluid w-100 rounded-top skeleton-effect" style="height: 300px; object-fit: cover;" alt="<?= esc($admin['nama']) ?>" loading="lazy" onload="this.classList.remove('skeleton-effect')">
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
<?= $this->include('pengunjung/layout/footer') ?>