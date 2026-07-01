<?php
/* ======================================================
   VIEW ERROR 404 (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna. View ini khusus untuk menampilkan pesan 
   ramah jika halaman yang dicari tidak ditemukan.
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('title') ?>
404 Page Not Found
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="d-flex align-items-center py-5 mt-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6 text-center">
        <div class="display-1 fw-bold text-primary lh-1 mb-3">404</div>
        <h1 class="h3 mb-3">Oops! Halaman tidak ditemukan.</h1>
        <p class="text-secondary mb-4">
          Kami tidak dapat menemukan halaman yang Anda cari. Anda dapat kembali ke dashboard untuk melanjutkan aktivitas Anda.
        </p>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
          Kembali ke dashboard
        </a>
      </div>
    </div>
  </div>
</main>
<?= $this->endSection() ?>
