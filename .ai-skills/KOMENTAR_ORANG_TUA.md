---
name: komentar-orang-tua
description: >
  Standar penulisan komentar kode untuk proyek SiWisata Balam.
  Semua komentar ditulis dengan bahasa awam (layman terms) agar mudah dipahami
  oleh orang tua, dosen penguji, atau developer pemula.
  Mencakup aturan untuk Model, View, Controller, Route, dan Script JavaScript.
---

# KONSEP TATA LETAK KOMENTAR ORANG TUA

Setiap kali AI menulis atau memodifikasi komentar di dalam kode (terutama untuk keperluan skripsi atau pembelajaran pemula), AI **WAJIB** menerapkan standar penulisan berikut agar mudah dimengerti layaknya menjelaskan kepada orang tua:

---

## Aturan Umum (Berlaku untuk Model, View, Controller, Route)

1. **Gunakan Bahasa Awam (Layman Terms):** Hindari jargon teknis yang membingungkan. Jelaskan kode layaknya bercerita kepada orang tua atau klien non-IT.
   _(Contoh: Jangan hanya bilang "MVC Router". Bilang: "Routes ibarat resepsionis yang mengarahkan link URL ke fungsi yang tepat.")_

2. **Komentar Bertingkat (Step-by-step):** Gunakan penomoran `(1)`, `(2)`, `(3)` untuk memecah alur logika fungsi yang panjang menjadi langkah-langkah yang masuk akal.
   _(Contoh: (1) Validasi input form, (2) Upload foto fisik ke server, (3) Simpan teks ke database)._

3. **Tata Letak Sejajar (Inline Comments di Sebelah Kanan Kode):** Jika memungkinkan dan untuk daftar variabel/konfigurasi, letakkan komentar _tepat di sebelah kanan_ baris kode agar terlihat rapi dan tidak memakan terlalu banyak ruang vertikal. Buat sejajar/rata kanan (menggunakan spasi).
   _(Lihat contoh penataan pada file model `Destinasi`, seperti `DestinasiFasilitasModel.php` atau `DestinasiModel.php` di mana penjelasan array diatur rapi di samping)._

4. **Super Spesifik & Detail:** Jelaskan setiap elemen penting secara rinci.
   _(Contoh: Alih-alih "Hapus data", tulis "Hapus file gambar fisiknya dari folder uploads/ menggunakan fungsi unlink() agar hardisk server tidak penuh, lalu hapus baris di tabel database.")_

5. **Komentar Header Lengkap (Blok Awal File):** Pada awal setiap file (Model, View, Controller, atau Route), berikan penjelasan blok besar yang memuat ringkasan tugas utama file tersebut beserta analoginya (misal: "Ibarat satpam penjaga tabel").

6. **Bedakan Jelas Input vs Kolom DB:** Saat terjadi mapping (array), berikan keterangan jelas mana variabel yang merupakan "Ketikan Pengguna dari Form HTML" dan mana yang murni "Nama Kolom Tabel di Database".

---

## Aturan Khusus File View (HTML/PHP)

Untuk file `View`, ubah komentar HTML standar (contoh: `<!-- Modal Tambah -->` atau `<!-- Charts -->`) menjadi blok komentar besar (`<!-- ====================================================== ... ====================================================== -->`). Jelaskan fungsi bagian antarmuka/UI tersebut layaknya bercerita.

### Wajib untuk setiap blok UI:

- **WAJIB MENYEBUTKAN DATA/KOLOM:** Sebutkan data atau kolom database apa saja yang sedang dipanggil di bagian tersebut (misal: `$admin['nama']`, `$admin['foto_profil']`).
- **WAJIB MENYEBUTKAN FOLDER FILE:** Jika ada pemanggilan gambar/file fisik, sebutkan dari folder mana file tersebut diambil (misal: folder `uploads/profil/`).
- **WAJIB MENJELASKAN LOOPING:** Jika terdapat perulangan (seperti `foreach`), jelaskan apa yang sedang diulang dan bagaimana prosesnya (misal: "Sistem akan mengecek semua data wisata satu per satu, dan membuatkan 1 kotak/card untuk setiap wisata.").

### Contoh Blok Komentar HTML View:

```html
<!-- ======================================================
     MODAL TAMBAH DESTINASI WISATA BARU

     Ini adalah formulir popup (modal) untuk menambahkan data wisata baru.
     Saat admin menekan tombol "Tambah Data", modal ini akan muncul.
     
     Daftar Kolom Isian (Input) dalam formulir ini:
     - Nama Wisata (name="nama_wisata")        : Wajib. Teks bebas.
     - Kategori (name="kategori_id")           : Wajib. Dropdown dari tabel `kategori_wisata`.
     - Thumbnail (name="thumbnail")            : Wajib. Upload gambar → disimpan di `uploads/thumbnail/`.
     - Deskripsi (name="deskripsi")             : Opsional. Textarea panjang.
     
     Saat tombol "Simpan" diklik, data dikirim ke Controller fungsi store()
     melalui method POST.
====================================================== -->
```

---

## Aturan Khusus Blok `<script>` JavaScript di View

Untuk blok `<script>` di dalam file View, komentar **WAJIB** mencakup:

1. **Blok Pembuka Script:** Berikan komentar besar sebelum tag `<script>` yang menjelaskan tujuan utama skrip tersebut.

2. **Alur Kerja Bernomor:** Jelaskan langkah demi langkah apa yang dilakukan skrip menggunakan format `(1)`, `(2)`, `(3)`:
   ```javascript
   /* ======================================================
      SKRIP JAVASCRIPT (KIRIM PESAN KE WHATSAPP)
      
      Alur Kerjanya:
      (1) Saat pengunjung menekan tombol "Kirim via WhatsApp",
          hentikan aksi bawaan HTML (e.preventDefault).
      (2) Ambil semua ketikan dari kolom form.
      (3) Rangkai menjadi satu pesan panjang.
      (4) Ubah teks menjadi format URL (encodeURIComponent).
      (5) Buka tab baru ke link wa.me beserta teks pesan.
   ====================================================== */
   ```

3. **Komentar Per-Langkah di Dalam Script:** Setiap tahapan utama di dalam blok `<script>` diberi komentar inline yang mengacu pada nomor langkah di blok pembuka:
   ```javascript
   // (1) Dengarkan aksi "Submit" dari formKontak
   document.getElementById('formKontak').addEventListener('submit', function(e) {
       e.preventDefault();
       
       // (2) Ambil ketikan pengguna
       var name = document.getElementById('name').value;
       
       // (3) Rangkai pesan
       var text = "Halo Admin,\n\n";
       text += "Nama: " + name + "\n";
       
       // (4) Ubah menjadi format URL yang aman
       var encodedText = encodeURIComponent(text);
       
       // (5) Buka jendela chat WhatsApp
       window.open("https://wa.me/" + waNumber + "?text=" + encodedText, "_blank");
   });
   ```

4. **Interaksi PHP-ke-JS:** Jika ada data PHP yang di-encode ke JSON untuk dipakai JavaScript, jelaskan secara eksplisit kolom-kolom apa saja yang diambil:
   ```javascript
   /* ======================================================
      PERSIAPAN DATA (ARRAY MAPPING)
      
      Kita memanggil data `$destinasi` dari Controller (tabel 'destinasi')
      lalu mengubahnya menjadi format JSON agar bisa dibaca oleh Javascript.
      
      Kolom DB yang diambil:
      - $d['id']              : ID wisata
      - $d['nama_wisata']     : Nama destinasi
      - $d['latitude']        : Koordinat lintang (untuk marker peta)
      - $d['longitude']       : Koordinat bujur (untuk marker peta)
      - $d['thumbnail']       : Foto sampul (dari folder `uploads/thumbnail/`)
   ====================================================== */
   var destinasi = <?= json_encode(...) ?>;
   ```

5. **Algoritma / Library Kompleks:** Jika script menggunakan algoritma (Haversine, Geolocation), jelaskan alur kerja komplet:
   ```javascript
   /* ======================================================
      SKRIP TOMBOL "LOKASI SAYA" (GEOLOCATION & HAVERSINE JS)
      
      Alur kerja saat pengunjung menekan tombol:
      (1) Menjalankan sensor GPS (navigator.geolocation).
      (2) Jika berhasil, kamera peta terbang (flyTo) ke titik pengguna.
      (3) Meletakkan Marker/Pin di posisi pengguna.
      (4) Looping semua data wisata → hitung jarak pakai Rumus Haversine.
      (5) Sortir data agar wisata terdekat di atas.
      (6) Tulis ulang daftar wisata di Sidebar.
      (7) Reverse Geocoding via OpenStreetMap untuk alamat teks.
   ====================================================== */
   ```

---

## Acuan & Contoh Terbaik

**Catatan:** Acuan utama (contoh terbaik) untuk tata letak komentar ini adalah file-file `Destinasi` (seperti `DestinasiController.php`, `DestinasiModel.php`, `DestinasiFasilitasModel.php` dan `app/Views/admin/destinasi/index.php`). Ikuti tata letak spasi, indentasi, dan penempatan komentarnya sebisa mungkin sama persis.

Untuk contoh komentar JavaScript/Script View, lihat:
- `app/Views/pengunjung/kontak.php` — Contoh script WhatsApp Form.
- `app/Views/pengunjung/peta.php` — Contoh script Leaflet + Geolocation + Haversine.
- `app/Views/pengunjung/rekomendasi.php` — Contoh script Geolocation + redirect URL.
- `app/Views/pengunjung/galeri.php` — Contoh script carousel modal + video control.
