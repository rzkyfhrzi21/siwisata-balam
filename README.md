# Sistem Informasi Destinasi Wisata Kota Bandar Lampung

Aplikasi berbasis web menggunakan CodeIgniter 4 untuk mengelola dan menyajikan informasi destinasi wisata, kategori, dan profil wisata di Kota Bandar Lampung.

## Persyaratan Sistem

- **PHP** 8.1 atau lebih baru
- **Composer**
- **Web Server & MySQL** (XAMPP, Laragon, MAMP)
- **Git** (opsional)

---

## Instalasi

### 1. Clone Repository
```bash
git clone <url-repository-ini>
cd wisata-bandar-lampung
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Konfigurasi `.env`
- Salin file `env` menjadi `.env`
- Set `CI_ENVIRONMENT = development`
- Konfigurasi database:
```env
database.default.hostname = localhost
database.default.database = db_wisata
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 4. Buat Database
- Buka phpMyAdmin (`http://localhost/phpmyadmin`)
- Buat database baru: `db_wisata`

### 5. Migrasi & Seed Database
```bash
php spark migrate
php spark db:seed MainSeeder
```

### 6. Jalankan Server
```bash
php spark serve
```
Akses di `http://localhost:8080`

---

## Troubleshooting

Jika terjadi error setelah memindahkan folder proyek:

```bash
del writable\cache\FileLocatorCache writable\cache\FactoriesCache_config
composer dump-autoload
php spark cache:clear
php spark serve
```

---

## Akses Sistem

- **Frontend:** `http://localhost:8080`
- **Admin Login:** `http://localhost:8080/admin/login`

**Kredensial Default:**
- Username: ``
- Password: ``

---

## Struktur Direktori

- `app/Controllers/` - Logic controller (Admin, Auth)
- `app/Views/` - File tampilan HTML
- `app/Models/` - Interaksi dengan database
- `public/assets/` - File statis (CSS, JS, gambar upload)
- `docs/` - Dokumentasi sistem

---

**Catatan:** Pastikan folder `public/assets/img/destinasi` memiliki izin tulis untuk upload gambar.
