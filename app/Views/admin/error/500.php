<?php
/* ======================================================
   VIEW ERROR 500 (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna. View ini khusus untuk menampilkan pesan
   peringatan bahwa ada masalah pada sistem/server.
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('title') ?>
500 Server Error
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="d-flex align-items-center py-5 mt-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6 text-center">
        <div class="display-1 fw-bold text-danger lh-1 mb-3">500</div>
        <h1 class="h3 mb-3">Terjadi kesalahan pada sistem.</h1>
        <p class="text-secondary mb-4">
          Server mengalami kondisi tak terduga. Tim teknis kami telah diberitahu mengenai insiden ini. Silakan coba lagi nanti, atau kembali ke halaman utama.
        </p>
        <div class="d-flex gap-2 justify-content-center">
          <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
            Kembali ke dashboard
          </a>
        </div>
      </div>
    </div>
  </div>
</main>
<?= $this->endSection() ?>