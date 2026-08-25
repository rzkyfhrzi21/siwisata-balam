<?php
/* ======================================================
   VIEW MANAJEMEN WEBSITE (MVC - VIEW)

   Halaman khusus pengaturan tampilan website publik.
   Isi utama: card "Kelola Background Website" untuk mengganti
   gambar hero beranda (3 slide carousel) dan background
   halaman lainnya, dengan SATU tombol simpan untuk semua.
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('title') ?>
Manajemen Website
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Manajemen Website</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ======================================================
     CARD KELOLA BACKGROUND WEBSITE

     Admin dapat mengganti gambar background hero:
     - Hero Beranda  : 3 gambar untuk slide carousel di halaman utama.
     - Hero Lainnya  : 1 gambar background untuk semua halaman sub
                       (Tentang, Destinasi, Galeri, Kontak, dll).
     Jika belum ada gambar yang diunggah, sistem otomatis memakai
     gambar default bawaan template.
====================================================== -->
<div class="card shadow mb-4">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-images me-1"></i> Kelola Background Website</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/website/simpan-hero') ?>" method="post" enctype="multipart/form-data" id="formHero">
            <?= csrf_field() ?>
            <div class="row g-4">

                <!-- Kolom Kiri: Hero Beranda (Carousel) -->
                <div class="col-lg-6">
                    <h6 class="text-primary fw-bold mb-3">Hero Beranda (Carousel)</h6>
                    <div class="row g-3">
                        <?php foreach ([1 => $hero['index1'], 2 => $hero['index2'], 3 => $hero['index3']] as $slideNo => $previewUrl): ?>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Slide <?= $slideNo ?></label>
                                <img src="<?= $previewUrl ?>" alt="Hero Slide <?= $slideNo ?>"
                                    class="w-100 rounded border mb-2 preview-hero"
                                    data-preview="slide<?= $slideNo ?>"
                                    style="height: 110px; object-fit: cover; background: #f8f9fa;">
                                <input type="file"
                                    class="form-control form-control-sm input-hero"
                                    name="index<?= $slideNo ?>"
                                    data-preview="slide<?= $slideNo ?>"
                                    accept="image/jpeg,image/png,image/webp">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Kolom Kanan: Hero Halaman Lain -->
                <div class="col-lg-6">
                    <h6 class="text-primary fw-bold mb-3">Background Halaman Lain</h6>
                    <p class="text-muted small mb-2">
                        Dipakai sebagai background judul halaman Tentang, Destinasi, Galeri, Kontak, dan detail wisata.
                    </p>
                    <img src="<?= $hero['umum'] ?>" alt="Background Halaman Lain"
                        class="w-100 rounded border mb-2 preview-hero"
                        data-preview="umum"
                        style="height: 150px; object-fit: cover; background: #f8f9fa;">
                    <input type="file"
                        class="form-control form-control-sm input-hero"
                        name="umum"
                        data-preview="umum"
                        accept="image/jpeg,image/png,image/webp">
                </div>

            </div>

            <hr class="my-4">

            <!-- Satu tombol simpan untuk SEMUA slot -->
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('/') ?>" target="_blank" class="btn btn-outline-secondary">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Website
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan Semua Background
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Preview langsung gambar terpilih sebelum disimpan.
    document.querySelectorAll('.input-hero').forEach(function (input) {
        input.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) return;

            const img = document.querySelector('img[data-preview="' + this.dataset.preview + '"]');
            if (!img) return;

            img.src = URL.createObjectURL(file);
        });
    });

    // Cegah submit jika tidak ada satu pun file yang dipilih.
    document.getElementById('formHero').addEventListener('submit', function (e) {
        const adaFile = [...document.querySelectorAll('.input-hero')].some(i => i.files.length > 0);
        if (!adaFile) {
            e.preventDefault();
        }
    });
});
</script>
<?= $this->endSection() ?>
