<?php
/* ======================================================
   VIEW KONTAK KAMI (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna/pengunjung web.
   
   Halaman ini adalah halaman Kontak yang menampilkan alamat fisik,
   email, nomor WA, serta sebuah formulir HTML murni yang jika diisi 
   akan mengarahkan pengunjung ke chat WhatsApp Admin.
====================================================== */
?>
<?= $this->include('pengunjung/layout/header') ?>

<!-- ======================================================
     BAGIAN HEADER HALAMAN (BREADCRUMB)
     
     Ini adalah area judul halaman besar di bagian atas,
     biasanya dilengkapi dengan jalur navigasi (breadcrumb)
     seperti "Home > Pages > Kontak".
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
     BAGIAN INFORMASI KONTAK & FORMULIR PESAN WA
     
     Bagian ini terbagi menjadi dua sisi:
     - Kiri  : Menampilkan blok statis alamat, nomor WA, dan Email admin.
     - Kanan : Sebuah form isian untuk mengirimkan pesan via WA.

     Daftar Kolom Isian (Input) Formulir Kanan:
     - Nama Anda (id='name')    : Wajib (Required). Teks bebas.
     - Email Anda (id='email')  : Wajib (Required). Teks format email (@).
     - Judul Pesan (id='subject'): Wajib (Required). Teks bebas.
     - Pesan (id='message')     : Wajib (Required). Teks area / paragraf.
     
     Perhatikan: Form ini *TIDAK* dikirim ke database, melainkan dirakit
     oleh Javascript di bawah untuk menjadi teks pesan WhatsApp.
====================================================== -->
<div class="container-fluid contact bg-light py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Hubungi Kami</h5>
            <h1 class="mb-0">Pusat Bantuan & Informasi</h1>
        </div>
        <div class="row g-5 align-items-center">
            <div class="col-lg-4">
                <div class="bg-white rounded p-4">
                    <div class="text-center mb-4">
                        <i class="fa fa-map-marker-alt fa-3x text-primary"></i>
                        <h4 class="text-primary">
                            <Address>Alamat</Address>
                        </h4>
                        <p class="mb-0">Kota Bandar Lampung, <br> Lampung, Indonesia</p>
                    </div>
                    <div class="text-center mb-4">
                        <i class="fa fa-phone-alt fa-3x text-primary mb-3"></i>
                        <h4 class="text-primary">WhatsApp</h4>
                        <p class="mb-0">+62 853-8293-4864</p>
                    </div>

                    <div class="text-center">
                        <i class="fa fa-envelope-open fa-3x text-primary mb-3"></i>
                        <h4 class="text-primary">Email</h4>
                        <p class="mb-0">rahayuwijayanti46@gmail.com</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <h3 class="mb-2">Kirim Pesan ke Kami</h3>
                <p class="mb-4">Jika Anda memiliki pertanyaan seputar destinasi wisata, kritik, atau saran, jangan ragu untuk menghubungi kami melalui formulir di bawah ini. Pesan Anda akan langsung diteruskan ke WhatsApp Admin SiWisata Balam.</p>
                <form id="formKontak">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0" id="name" placeholder="Nama Anda" required>
                                <label for="name">Nama Anda</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control border-0" id="email" placeholder="Email Anda" required>
                                <label for="email">Email Anda</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0" id="subject" placeholder="Judul Pesan" required>
                                <label for="subject">Judul Pesan</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control border-0" placeholder="Tulis pesan Anda di sini" id="message" style="height: 160px" required></textarea>
                                <label for="message">Pesan</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" type="submit"><i class="fab fa-whatsapp me-2"></i>Kirim via WhatsApp</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- ======================================================
                 PETA GOOGLE MAPS (IFRAME)
                 Embed Peta dari Google Maps untuk menunjukkan lokasi kantor 
                 atau kampus secara visual di bagian bawah halaman kontak.
            ====================================================== -->
            <div class="col-12">
                <div class="rounded">
                    <iframe class="rounded w-100"
                        style="height: 450px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.2577789155926!2d105.247044625435!3d-5.377611694601238!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40dac5f1bf788b%3A0x2458668e7c62825f!2sInstitut%20Informatika%20dan%20Bisnis%20Darmajaya!5e0!3m2!1sid!2sid!4v1782546585236!5m2!1sid!2sid"
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ======================================================
     SKRIP JAVASCRIPT (KIRIM PESAN KE WHATSAPP)
     
     Fungsi script di bawah ini adalah untuk merakit teks dari form
     menjadi tautan URL WhatsApp API (wa.me).
     
     Alur Kerjanya:
     (1) Saat pengunjung menekan tombol "Kirim via WhatsApp", hentikan 
         aksi bawaan HTML yang biasanya me-refresh halaman (e.preventDefault).
     (2) Ambil/baca semua ketikan dari kolom form (name, email, subject, message).
     (3) Rangkai (gabungkan) semua teks tersebut menjadi satu pesan panjang.
     (4) Enkripsi/Ubah teks menjadi format URL (encodeURIComponent) agar 
         spasi menjadi %20 dan enter menjadi %0A.
     (5) Buka tab (jendela) baru di browser yang mengarah ke link wa.me 
         milik nomor Admin, beserta isi teks pesan yang sudah dirangkai.
====================================================== -->
<script>
    // (1) Dengarkan aksi "Submit" dari formKontak
    document.getElementById('formKontak').addEventListener('submit', function(e) {
        e.preventDefault();

        // (2) Ambil ketikan pengguna
        var name = document.getElementById('name').value;
        var email = document.getElementById('email').value;
        var subject = document.getElementById('subject').value;
        var message = document.getElementById('message').value;

        // (3) Rangkai pesan
        var text = "Halo Admin SiWisata Balam,\n\n";
        text += "Nama: " + name + "\n";
        text += "Email: " + email + "\n";
        text += "Judul: " + subject + "\n\n";
        text += "Pesan:\n" + message;

        // (4) Ubah menjadi format URL yang aman
        var encodedText = encodeURIComponent(text);
        
        // Nomor admin tujuan
        var waNumber = "6285382934864";

        // (5) Buka jendela chat WhatsApp
        window.open("https://wa.me/" + waNumber + "?text=" + encodedText, "_blank");
    });
</script>

<?= $this->include('pengunjung/layout/footer') ?>
