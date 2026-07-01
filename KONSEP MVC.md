# Konsep MVC & Algoritma pada SiWisata Balam

Dokumen ini menjelaskan alur kerja konsep **Model-View-Controller (MVC)** yang diterapkan pada fitur Manajemen Destinasi Wisata, beserta implementasi **Algoritma Haversine** pada sistem rekomendasi terdekat. Penjelasan ini disusun menggunakan bahasa awam agar mudah dipahami, layaknya menceritakan alur sebuah restoran.

---

## 1. Alur Kerja MVC (Manajemen Destinasi)

Pada proyek ini, arsitektur MVC memisahkan logika aplikasi menjadi tiga komponen utama layaknya sebuah restoran:

- **Model (`DestinasiModel.php`)**: Ibarat **Koki/Gudang Bahan Makanan**. Bertanggung jawab berinteraksi langsung dengan database (tabel `destinasi`). Hanya dia yang punya akses untuk mengambil, menyimpan, mengedit, atau menghapus data ke database.
- **View**: Ibarat **Pelayan & Buku Menu**. Menampilkan antarmuka pengguna (tampilan layar), baik untuk pengunjung publik (`app/Views/pengunjung/destinasi.php`) maupun layar khusus untuk admin (`app/Views/admin/destinasi/index.php`).
- **Controller**: Ibarat **Manajer Restoran**. Mengatur lalu lintas data. Jika ada pengguna menekan tombol, Controller yang menangkap perintahnya, menyuruh Model mengambil data, lalu menyuruh View untuk menampilkan datanya. File utamanya: `app/Controllers/Admin/DestinasiController.php`.

### A. Fitur Lihat Destinasi (Read)

**Admin (Halaman Belakang - `http://localhost:8080/admin/destinasi`)**
Menampilkan daftar seluruh destinasi wisata dalam bentuk tabel dengan opsi manajemen (Tambah/Edit/Hapus).

- **Controller**: Bertugas memanggil Model untuk meminta semua data, lalu menyerahkannya ke View.
- **Model**: Bertugas menarik baris-baris data dari tabel database MySQL.
- **View**: Merakit data dari Controller menjadi bentuk tabel HTML yang cantik.

**Cuplikan Kode (Controller):**

```php
public function index()
{
    // 1. Panggil Model Fasilitas
    $fasilitasModel = new \App\Models\FasilitasModel();
    
    // 2. Minta Model Destinasi mengambil seluruh data dari tabel, urutkan dari yang terbaru (DESC)
    $destinasi = $this->destinasiModel->select('destinasi.*, kategori_wisata.nama_kategori')
        ->join('kategori_wisata', 'kategori_wisata.id = destinasi.kategori_id', 'left')
        ->orderBy('destinasi.id', 'DESC')
        ->findAll();

    // 3. Bungkus data ke dalam keranjang bernama $data
    $data = [
        'title' => 'Manajemen Destinasi Wisata',
        'destinasi' => $destinasi,
        'kategori' => $this->kategoriModel->findAll(),
        'fasilitas_list' => $fasilitasModel->findAll()
    ];
    
    // 4. Buka halaman (view) index.php sambil membawa keranjang $data
    return view('admin/destinasi/index', $data);
}
```

---

### B. Fitur Tambah Destinasi (Create)

Berfungsi menambahkan data tempat wisata baru dari ketikan admin ke dalam database beserta unggahan file gambar (thumbnail).

**Cuplikan Kode (Controller):**

```php
public function store()
{
    // 1. Validasi Input (Periksa apakah isian form sudah lengkap dan nama tidak kembar)
    $rules = [
        'nama_wisata' => 'required|is_unique[destinasi.nama_wisata]',
        'kategori_id' => 'required',
        // ... aturan lainnya
    ];

    // 2. Upload Gambar
    // Ambil file foto fisik dari komputer pengguna
    $fotoFile = $this->request->getFile('thumbnail');
    if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
        $namaFoto = $fotoFile->getRandomName(); // Beri nama acak
        $fotoFile->move('uploads/thumbnail', $namaFoto); // Pindahkan ke folder server agar tidak hilang
    }

    // 3. Persiapan Simpan ke Database
    // Sebelah kiri adalah nama kolom di database, sebelah kanan (getPost) adalah teks ketikan admin dari form
    $data = [
        'nama_wisata' => $this->request->getPost('nama_wisata'),
        'slug' => url_title($this->request->getPost('nama_wisata'), '-', true),
        // ... kolom lainnya
        'thumbnail' => $namaFoto, // Simpan nama file fotonya saja (misal: "pantai.jpg")
    ];
    
    // 4. Perintahkan Model untuk menyimpan data array tersebut ke database
    $this->destinasiModel->insert($data);

    // 5. Tendang (redirect) admin kembali ke halaman tabel dengan pesan sukses
    return redirect()->to('/admin/destinasi')->with('success', 'Berhasil ditambahkan');
}
```

---

### C. Fitur Ubah Destinasi (Update)

Memperbarui data destinasi yang sudah ada. Jika admin mengunggah foto baru, foto lama secara otomatis dihapus agar tidak menumpuk di memori server.

**Cuplikan Kode (Controller):**

```php
public function update($id)
{
    // 1. Cari data aslinya di database berdasarkan ID
    $destinasi = $this->destinasiModel->find($id);

    // 2. Siapkan data perubahan dari form
    $data = [
        'nama_wisata' => $this->request->getPost('nama_wisata'),
        // ... ambil ketikan lainnya
    ];

    // 3. Proses ganti foto (Jika ada foto baru yang diupload)
    $fotoFile = $this->request->getFile('thumbnail');
    if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
        $namaFoto = $fotoFile->getRandomName();
        $fotoFile->move('uploads/thumbnail', $namaFoto); // Simpan foto baru
        $data['thumbnail'] = $namaFoto;

        // Hapus (unlink) file foto lama secara fisik dari folder server 
        // menggunakan FCPATH (alamat rute sistem ke folder root)
        if ($destinasi['thumbnail'] && file_exists(FCPATH . 'uploads/thumbnail/' . $destinasi['thumbnail'])) {
            unlink(FCPATH . 'uploads/thumbnail/' . $destinasi['thumbnail']);
        }
    }

    // 4. Update via Model
    $this->destinasiModel->update($id, $data);
    return redirect()->to('/admin/destinasi')->with('success', 'Berhasil diubah');
}
```

---

### D. Fitur Hapus Destinasi (Delete)

Menghapus rekaman destinasi dari database. Tidak hanya data teks yang dihapus, file fisik (foto utama dan foto galeri) juga akan dimusnahkan.

**Cuplikan Kode (Controller):**

```php
public function destroy($id)
{
    // 1. Temukan datanya dulu
    $destinasi = $this->destinasiModel->find($id);
    if ($destinasi) {
        
        // 2. Hapus file gambar utama dari folder menggunakan fungsi unlink()
        if ($destinasi['thumbnail'] && file_exists(FCPATH . 'uploads/thumbnail/' . $destinasi['thumbnail'])) {
            unlink(FCPATH . 'uploads/thumbnail/' . $destinasi['thumbnail']);
        }

        // 3. Hapus baris data dari database
        $this->destinasiModel->delete($id);
        
        // 4. Kembalikan pengguna ke halaman sebelumnya
        return redirect()->back()->with('success', 'Data wisata berhasil dihapus');
    }
}
```

---

## 2. Implementasi Algoritma Haversine (Rekomendasi Terdekat)

**URL Akses:** `http://localhost:8080/rekomendasi`

**Apa itu Algoritma Haversine?**
Bayangkan bumi itu bulat seperti bola. Jika kita ingin mengukur jarak lurus antara dua titik (Lokasi Anda vs Lokasi Wisata) di atas permukaan bola, kita tidak bisa memakai rumus garis lurus biasa (Pythagoras). Kita butuh rumus trigonometri yang memperhitungkan lengkungan bumi. Rumus itulah yang dinamakan **Algoritma Haversine**.

Sistem ini mengambil koordinat lokasi pengguna saat ini (berdasarkan GPS HP/Laptop) lalu membandingkannya dengan koordinat seluruh tempat wisata di database, kemudian mengurutkannya dari yang berjarak paling kecil (paling dekat).

### A. Modularisasi Logika Matematika

Rumus ini dipisah dari file utama dan diletakkan di file khusus `App\Libraries\Haversine.php` agar kodingan tetap bersih dan rapi.

**Cuplikan Kode (`app/Libraries/Haversine.php`):**

```php
class Haversine
{
    // Konstanta standar internasional: Jari-jari (Radius) bumi = 6371 KM
    public const EARTH_RADIUS_KM = 6371; 

    // Fungsi Kalkulator Pencari Jarak
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        // 1. Konversi derajat lintang/bujur (format GPS) ke radian 
        // karena rumus matematika PHP hanya menerima format radian.
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        // 2. Penerapan rumus Trigonometri sferis (Haversine Formula) yang sangat rumit
        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        // 3. Kalikan sudut lengkung bumi dengan jari-jari bumi
        $distance = self::EARTH_RADIUS_KM * $c;

        // 4. Kembalikan hasil jarak yang sudah dipotong (dibulatkan) jadi 2 angka desimal (contoh: 5.43 KM)
        return round($distance, 2); 
    }
}
```

### B. Pemanggilan di Halaman Depan Pengunjung

Pada Controller untuk pengunjung (`Home.php`), sistem akan mengecek (melakukan "looping") seluruh wisata satu per satu, mengukurnya dengan Haversine, lalu disortir (diurutkan).

**Cuplikan Kode (`Home::rekomendasi()`):**

```php
public function rekomendasi()
{
    // 1. Tangkap titik lokasi (GPS) pengunjung
    $lat = $this->request->getGet('lat');
    $lon = $this->request->getGet('lon');

    if ($lat !== null && $lon !== null) {
        // 2. Ambil seluruh data wisata dari database yang punya titik koordinat
        $semuaDestinasi = $this->destinasiModel
            ->where('latitude IS NOT NULL')
            ->where('longitude IS NOT NULL')
            ->findAll();

        // 3. Cek jaraknya satu per satu menggunakan kalkulator Haversine tadi
        foreach ($semuaDestinasi as &$d) {
            $d['jarak_km'] = \App\Libraries\Haversine::calculateDistance(
                (float)$lat, (float)$lon,
                (float)$d['latitude'], (float)$d['longitude']
            );
        }

        // 4. Urutkan posisi (sort) dari jarak terkecil (terdekat) ke terbesar
        usort($semuaDestinasi, fn($a, $b) => $a['jarak_km'] <=> $b['jarak_km']);

        // 5. Potong hasilnya, cukup ambil 9 lokasi teratas yang paling dekat
        $destinasiSorted = array_slice($semuaDestinasi, 0, 9);
    }

    // ... tampilkan data ke View (Layar Pengunjung)
}
```
