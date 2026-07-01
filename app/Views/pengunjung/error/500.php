<?php
/* ======================================================
   VIEW ERROR 500 (MVC - VIEW)
   
   Apa itu View Error 500?
   Ini adalah halaman peringatan "Kerusakan Internal". 
   Akan otomatis muncul JIKA ada kode PHP yang error atau
   koneksi ke database terputus.
====================================================== */
?>
<?= $this->include('pengunjung/layout/header') ?>

<!-- ======================================================
     BAGIAN HEADER HALAMAN (BREADCRUMB)
     Area judul besar yang menyatakan bahwa halaman Error.
====================================================== -->
<div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h3 class="text-white display-3 mb-4">500 Server Error</h3>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
            <li class="breadcrumb-item active text-white">500 Error</li>
        </ol>    
    </div>
</div>

<!-- ======================================================
     BAGIAN PESAN ERROR
     Menampilkan ikon peringatan server dan penjelasan bahwa
     sedang ada kendala sistem di sisi admin/programmer.
====================================================== -->
<div class="container-fluid py-5" style="background: linear-gradient(rgba(19, 53, 123, 0.3), rgba(19, 53, 153, 0.3)); object-fit: cover;">
    <div class="container py-5 text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="bi bi-server display-1 text-danger"></i>
                <h1 class="display-1 text-danger">500</h1>
                <h1 class="mb-4 text-dark">Terjadi Kesalahan Server</h1>
                <p class="mb-4 text-dark">Maaf, server mengalami kondisi tak terduga. Kami telah mencatat insiden ini. Silakan coba lagi dalam beberapa menit, atau kembali ke beranda.</p>
                <a class="btn btn-primary rounded-pill py-3 px-5" href="<?= base_url('/') ?>">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
<?= $this->include('pengunjung/layout/footer') ?>
