# Sistem Informasi Destinasi Wisata Kota Bandar Lampung

Sistem Informasi Destinasi Wisata Kota Bandar Lampung adalah sebuah aplikasi berbasis web (menggunakan framework CodeIgniter 4) yang dirancang untuk mengelola dan menyajikan informasi seputar destinasi wisata, kategori, dan profil wisata di Kota Bandar Lampung.

Berikut adalah panduan lengkap langkah demi langkah untuk menginstal dan menjalankan proyek ini di lingkungan lokal Anda.

---

## 🛠️ Persyaratan Sistem (Prerequisites)

Sebelum memulai, pastikan perangkat komputer Anda sudah terinstal aplikasi/software berikut:

1. **PHP** (Minimal versi 8.1 atau lebih baru).
2. **Composer** (Dependency Manager untuk PHP).
3. **Web Server & Database MySQL** (Bisa menggunakan XAMPP, Laragon, MAMP, dsb).
4. **Git** (Opsional, jika ingin melakukan _clone_ repository).

---

## 🚀 Panduan Instalasi (Step-by-Step)

### 1. Unduh / Clone Repository

Buka terminal (Command Prompt / PowerShell / Git Bash) dan _clone_ proyek ini ke dalam folder lokal Anda (misal di folder `htdocs` jika menggunakan XAMPP):

```bash
git clone <url-repository-ini>
cd wisata-bandar-lampung
```

### 2. Instalasi Library & Dependensi (Composer)

Proyek ini membutuhkan beberapa _library_ dari pihak ketiga. Instal semua _library_ menggunakan Composer dengan menjalankan perintah berikut di dalam terminal (pastikan posisi berada di dalam folder proyek):

```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)

1. Di dalam folder proyek, cari file bernama `env`.
2. Salin/Gandakan (Copy) file tersebut dan ubah namanya menjadi `.env`.
3. Buka file `.env` menggunakan _text editor_ (VS Code / Notepad++).
4. Ubah konfigurasi **Environment** menjadi _development_ agar Anda bisa melihat pesan _error_ jika terjadi kendala:
   ```env
   CI_ENVIRONMENT = development
   ```
5. Ubah konfigurasi **Database** dengan menghapus tanda pagar `#` pada baris database, dan sesuaikan isinya:
   ```env
   database.default.hostname = localhost
   database.default.database = db_wisata
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   database.default.DBPrefix =
   database.default.port = 3306
   ```
   _(Catatan: Sesuaikan `password` jika MySQL Anda menggunakan password. Umumnya XAMPP menggunakan `root` dengan password kosong)._

### 4. Buat Database Kosong di MySQL

1. Buka dan jalankan **Apache** serta **MySQL** melalui XAMPP/Laragon Control Panel.
2. Buka browser dan akses **phpMyAdmin** (biasanya di `http://localhost/phpmyadmin`).
3. Buat database baru dengan nama yang sama persis seperti di konfigurasi `.env`, yaitu: **`db_wisata`**.

### 5. Migrasi Database (Migration)

Agar struktur tabel otomatis dibuat di dalam _database_, jalankan perintah _migrate_ dari bawaan CodeIgniter 4 melalui terminal:

```bash
php spark migrate
```

_Tunggu hingga proses selesai. Perintah ini akan membuat semua tabel yang dibutuhkan (admin, destinasi, fasilitas, galeri, dll) sesuai rancangan sistem._

### 6. Isi Data Awal (Database Seeding)

Setelah tabel berhasil terbuat, isi data tabel (_dummy_ & data asli awal) seperti akun _Admin_, daftar _Kategori_, daftar _Fasilitas_, dan _Destinasi_ menggunakan _Seeder_. Jalankan perintah berikut:

```bash
php spark db:seed MainSeeder
```

### 7. Jalankan Aplikasi (Local Development Server)

Setelah semua instalasi dan konfigurasi _database_ selesai, jalankan server pengembangan lokal dengan mengetikkan perintah berikut:

```bash
php spark serve
```

Terminal akan menampilkan informasi URL akses (biasanya `http://localhost:8080`).

---

## 🛠️ Troubleshooting (Penyelesaian Masalah)

Jika Anda memindahkan folder proyek ke lokasi baru atau mendapati error terkait file tidak ditemukan (seperti saat menjalankan `php spark serve`), sisa path lama mungkin masih tersimpan di dalam _cache_. Lakukan langkah-langkah berikut secara berurutan pada terminal:

1. **Hapus File Cache Statis CI4 Secara Manual:**
   ```bash
   del writable\cache\FileLocatorCache writable\cache\FactoriesCache_config
   ```
2. **Generate Ulang Autoload Composer:**
   ```bash
   composer dump-autoload
   ```
3. **Bersihkan Cache Framework (Opsional):**
   ```bash
   php spark cache:clear
   ```
4. **Jalankan Kembali Server:**
   ```bash
   php spark serve
   ```

---

## 💻 Cara Akses Sistem

Buka browser pilihan Anda (Chrome, Firefox, Safari) dan akses tautan berikut:

- **Halaman Utama (Frontend):** `http://localhost:8080` (Akan segera tersedia/disesuaikan)
- **Halaman Login Admin:** `http://localhost:8080/admin/login` atau `http://localhost:8080/admin`

**Kredensial Login Default:**

- **Username:** ``
- **Password:** ``

---

## 📂 Struktur Direktori Penting

- `app/Controllers/` - Berisi _logic_ controller PHP (Admin, Auth, dll).
- `app/Views/` - Berisi file HTML/Tampilan antar muka (UI/UX).
- `app/Models/` - Berisi struktur perantara interaksi dengan database.
- `public/assets/` - Lokasi _file statis_ seperti CSS, JavaScript, _Library vendor_ (Tabulator, SweetAlert, dll), dan tempat penyimpanan **gambar destinasi** yang di-_upload_.
- `docs/` - Berisi dokumentasi sistem tambahan seperti `DATABASE_DESIGN.md` dan struktur referensi.

---

**Selamat Mengembangkan!** 🚀
Jika Anda menemui kendala _error permission_ pada folder penyimpan unggahan gambar (`public/assets/img/destinasi`), pastikan _folder_ tersebut memiliki izin tulis (write permission) yang sesuai pada OS Anda.
