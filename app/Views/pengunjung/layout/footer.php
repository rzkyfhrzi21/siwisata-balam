<?php
/* ======================================================
   VIEW LAYOUT FOOTER (MVC - VIEW)
   
   Apa itu View Layout?
   Ini adalah potongan kodingan (Template) yang menyambung
   dari file header.php. File ini selalu diletakkan di bagian paling
   bawah pada setiap halaman pengunjung.
   Berisi elemen Footer (Informasi Kontak, Hak Cipta) dan script JS penutup.
====================================================== */
?>
<!-- ======================================================
     BAGIAN FORM BERLANGGANAN (TIDAK AKTIF/DISEMBUNYIKAN)
====================================================== -->
<!-- <div class="container-fluid subscribe py-5">
    <div class="container text-center py-5">
        <div class="mx-auto text-center" style="max-width: 900px;">
            <h5 class="subscribe-title px-3">Subscribe</h5>
            <h1 class="text-white mb-4">Our Newsletter</h1>
            <p class="text-white mb-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum tempore nam, architecto doloremque velit explicabo? Voluptate sunt eveniet fuga eligendi! Expedita laudantium fugiat corrupti eum cum repellat a laborum quasi.
            </p>
            <div class="position-relative mx-auto">
                <input class="form-control border-primary rounded-pill w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                <button type="button" class="btn btn-primary rounded-pill position-absolute top-0 end-0 py-2 px-4 mt-2 me-2">Subscribe</button>
            </div>
        </div>
    </div>
</div> -->
<!-- Subscribe End -->

<!-- ======================================================
     BAGIAN KAKI HALAMAN (FOOTER UTAMA)
     
     Ini adalah blok informasi yang terletak di bawah halaman.
     Terdiri dari 4 kolom:
     1. Profil Singkat   : Memanggil variabel dari file `.env` (Lingkungan server)
                           seperti `env('app.siteName')` dan `env('app.desc')`.
     2. Navigasi Cepat   : Link langsung ke halaman-halaman utama.
     3. Kategori Wisata  : Looping mencari 6 data kategori langsung dari database 
                           menggunakan model `KategoriWisataModel`.
     4. Hubungi Kami     : Memanggil email, WA, IG dari file `.env`.
====================================================== -->
<div class="container-fluid footer py-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 text-white"><?= esc(env('app.siteName')) ?></h4>
                    <p class="text-white mb-3">
                        <?= esc(env('app.desc')) ?>
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 text-white">Navigasi</h4>
                    <a href="<?= base_url('tentang') ?>"><i class="fas fa-angle-right me-2"></i> Tentang Kami</a>
                    <a href="<?= base_url('destinasi') ?>"><i class="fas fa-angle-right me-2"></i> Destinasi</a>
                    <a href="<?= base_url('peta') ?>"><i class="fas fa-angle-right me-2"></i> Peta Interaktif</a>
                    <a href="<?= base_url('rekomendasi') ?>"><i class="fas fa-angle-right me-2"></i> Rekomendasi</a>
                    <a href="<?= base_url('galeri') ?>"><i class="fas fa-angle-right me-2"></i> Galeri</a>
                    <a href="<?= base_url('layanan') ?>"><i class="fas fa-angle-right me-2"></i> Layanan</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 text-white">Kategori Wisata</h4>
                    <?php
                    $kategoriModelFooter = new \App\Models\KategoriWisataModel();
                    $footerKategories = $kategoriModelFooter->findAll(6);
                    if (!empty($footerKategories)):
                        foreach ($footerKategories as $fk):
                    ?>
                            <a href="<?= base_url('destinasi/' . $fk['slug']) ?>"><i class="fas fa-angle-right me-2"></i> <?= esc($fk['nama_kategori']) ?></a>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <?php
                $waNumber = env('app.contactWA');
                $waLink = $waNumber;
                if (substr($waNumber, 0, 1) === '0') $waLink = '62' . substr($waNumber, 1);
                $igUsername = ltrim(env('app.instagram'), '@');
                ?>
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 text-white">Hubungi Kami</h4>
                    <a href="mailto:<?= esc(env('app.contactEmail')) ?>"><i class="fas fa-envelope me-2"></i> <?= esc(env('app.contactEmail')) ?></a>
                    <a href="https://wa.me/<?= esc($waLink) ?>" target="_blank"><i class="fas fa-phone me-2"></i> <?= esc($waNumber) ?></a>
                    <div class="d-flex align-items-center mt-3">
                        <a class="btn-square btn btn-primary rounded-circle mx-1" href="https://instagram.com/<?= esc($igUsername) ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a class="btn-square btn btn-primary rounded-circle mx-1" href="https://wa.me/<?= esc($waLink) ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- ======================================================
     BAGIAN HAK CIPTA (COPYRIGHT)
     Menampilkan tahun hak cipta secara otomatis (contoh: 2026 - 2027)
     dengan menggunakan fungsi bawaan PHP `date('Y')`.
====================================================== -->
<div class="container-fluid copyright text-body py-4">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-md-8 text-center text-md-end mb-md-0">
                <i class="fas fa-copyright me-2"></i><?= (date('Y') == '2026') ? '2026' : '2026 - ' . date('Y') ?> <a class="text-white" href="<?= base_url() ?>">Sistem Informasi Destinasi Wisata Kota Bandar Lampung</a>, Hak Cipta dilindungi undang-undang.
            </div>
            <div class="col-md-4 text-center text-md-start">
                Desain oleh <a class="text-white" href="https://instagram.com/rahayu.wij" target="_blank">Rahayu Wijayanti</a>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Copyright End -->

<!-- Back to Top -->
<a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>


<!-- ======================================================
     BAGIAN PEMANGGILAN FILE JAVASCRIPT (LIBRARIES)
     Semua mesin penggerak halaman web seperti jQuery, Bootstrap JS, 
     animasi OwlCarousel, dsb dipanggil di bagian paling bawah 
     agar tidak membuat web lambat saat pertama kali dimuat.
====================================================== -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/lib/easing/easing.min.js') ?>"></script>
<script src="<?= base_url('assets/lib/waypoints/waypoints.min.js') ?>"></script>
<script src="<?= base_url('assets/lib/owlcarousel/owl.carousel.min.js') ?>"></script>
<script src="<?= base_url('assets/lib/lightbox/js/lightbox.min.js') ?>"></script>


<!-- Template Javascript -->
<script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>

</html>