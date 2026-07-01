<?php
/* ======================================================
   VIEW ERROR 404 (MVC - VIEW)
   
   Apa itu View Error 404?
   Ini adalah halaman khusus yang akan otomatis muncul 
   JIKA pengunjung mencoba mengakses alamat URL wisata atau 
   halaman yang sudah dihapus atau tidak pernah ada.
====================================================== */
?>
<?= $this->include('pengunjung/layout/header') ?>

<!-- ======================================================
     BAGIAN HEADER HALAMAN (BREADCRUMB)
     Area judul besar yang menyatakan bahwa halaman Error.
====================================================== -->
<div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h3 class="text-white display-3 mb-4">404 Tidak Ditemukan</h3>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
            <li class="breadcrumb-item active text-white">404 Error</li>
        </ol>    
    </div>
</div>

<!-- ======================================================
     BAGIAN PESAN ERROR
     Menampilkan ikon peringatan besar dan tombol untuk kembali 
     ke halaman utama (Beranda) agar pengunjung tidak tersesat.
====================================================== -->
<div class="container-fluid py-5" style="background: linear-gradient(rgba(19, 53, 123, 0.3), rgba(19, 53, 153, 0.3)); object-fit: cover;">
    <div class="container py-5 text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="bi bi-exclamation-triangle display-1 text-primary"></i>
                <h1 class="display-1">404</h1>
                <h1 class="mb-4 text-dark">Halaman Tidak Ditemukan</h1>
                <p class="mb-4 text-dark">Maaf, halaman yang Anda cari tidak ada di website kami atau mungkin telah dipindahkan. Silakan kembali ke beranda.</p>
                <a class="btn btn-primary rounded-pill py-3 px-5" href="<?= base_url('/') ?>">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
<?= $this->include('pengunjung/layout/footer') ?>
