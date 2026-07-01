<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DestinasiModel;
use App\Models\GaleriModel;
use App\Libraries\ActivityLogService;

/* ======================================================
   CONTROLLER GALERI WISATA (MVC - CONTROLLER)

   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan
   database pengolahan data (Model). Jika ada pengguna yang mengklik
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function index()   = Mengambil seluruh data galeri lalu menampilkannya berdasarkan kategori wisata.
   - function store()   = Mengunggah foto atau video baru ke server dan menyimpannya ke database.
   - function destroy() = Menghapus file media dari server beserta data pada database.
====================================================== */

class GaleriController extends BaseController
{
    protected $destinasiModel;
    protected $galeriModel;

    /* ======================================================
       PERSIAPAN CONTROLLER - function __construct()

       Fungsi ini dijalankan otomatis saat Controller dipanggil.
       Tujuannya untuk menyiapkan Model agar dapat digunakan oleh
       seluruh fungsi di dalam Controller ini.
    ====================================================== */
    public function __construct()
    {
        // Panggil Model Destinasi dan Model Galeri agar Controller
        // dapat berkomunikasi dengan tabel database yang diperlukan.
        $this->destinasiModel = new DestinasiModel();
        $this->galeriModel    = new GaleriModel();
    }

    /* ======================================================
       FITUR BACA & TAMPIL DATA (READ) - function index()

       1. Ambil seluruh data kategori, destinasi, dan galeri.
       2. Kelompokkan destinasi berdasarkan kategori.
       3. Hitung statistik galeri.
       4. Bungkus seluruh hasil ke dalam variabel $data.
       5. Kirim dan tampilkan data tersebut ke halaman HTML/View.
    ====================================================== */
    public function index()
    {
        // (1) Panggil Model Kategori untuk mengambil data kategori wisata.
        $kategoriModel = new \App\Models\KategoriWisataModel();

        // Ambil seluruh data kategori dan destinasi dari database.
        $kategoriList  = $kategoriModel->findAll();
        $destinasiList = $this->destinasiModel->findAll();

        // Hitung jumlah seluruh foto dan video yang tersimpan pada database.
        $totalFoto  = $this->galeriModel->where('tipe_file', 'foto')->countAllResults();
        $totalVideo = $this->galeriModel->where('tipe_file', 'video')->countAllResults();

        // (2) Siapkan wadah kosong untuk setiap kategori wisata.
        $grouped = [];

        foreach ($kategoriList as $k) {
            $grouped[$k['id']] = [
                'nama_kategori' => $k['nama_kategori'],
                'destinasi'     => []
            ];
        }

        // Masukkan setiap destinasi ke dalam kategori yang sesuai.
        foreach ($destinasiList as $destinasi) {

            // Ambil seluruh foto dan video milik destinasi ini.
            $destinasi['galeri'] = $this->galeriModel
                ->where('destinasi_id', $destinasi['id'])
                ->findAll();

            // Jika kategori ditemukan, masukkan destinasi ke kelompok tersebut.
            if (isset($grouped[$destinasi['kategori_id']])) {
                $grouped[$destinasi['kategori_id']]['destinasi'][] = $destinasi;
            }
        }

        // Hilangkan kategori yang belum memiliki destinasi wisata.
        $grouped = array_filter($grouped, function ($g) {
            return count($g['destinasi']) > 0;
        });

        // (3) Bungkus seluruh hasil pengolahan ke dalam
        // satu keranjang/variabel bernama $data.
        $data = [
            'title'            => 'Manajemen Galeri Wisata',
            'groupedDestinasi' => $grouped,
            'totalDestinasi'   => count($destinasiList),
            'totalFoto'        => $totalFoto,
            'totalVideo'       => $totalVideo,
        ];

        // (4) Kembalikan (return) hasil proses ini dengan memuat (view)
        // file halaman 'admin/galeri/index.php'.
        // Jangan lupa bawa keranjang $data-nya ke halaman tersebut.
        return view('admin/galeri/index', $data);
    }

    /* ======================================================
       FITUR TAMBAH DATA (CREATE) - function store()

       1. Ambil data dan file dari Form HTML.
       2. Validasi jumlah file yang boleh diunggah.
       3. Validasi jenis dan ukuran file.
       4. Simpan file ke folder server.
       5. Simpan data file ke database.
       6. Catat aktivitas admin.
       7. Kembali ke halaman sebelumnya.
    ====================================================== */
    public function store()
    {
        // (1) Ambil data yang dikirim dari Form HTML.
        $destinasi_id = $this->request->getPost('destinasi_id');
        $files        = $this->request->getFiles();

        // Pastikan pengguna memilih minimal satu file.
        if (empty($files['media'])) {
            return redirect()->back()->with('error', 'Pilih minimal satu file foto atau video.');
        }

        // Cari data destinasi berdasarkan ID yang dipilih.
        $destinasi = $this->destinasiModel->find($destinasi_id);

        if (!$destinasi) {
            return redirect()->back()->with('error', 'Destinasi tidak ditemukan.');
        }

        // (2) Hitung jumlah file yang sudah tersimpan pada destinasi ini.
        $existingCount = $this->galeriModel
            ->where('destinasi_id', $destinasi_id)
            ->countAllResults();

        // Hitung jumlah file baru yang sedang diunggah.
        $incomingCount = 0;

        foreach ($files['media'] as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $incomingCount++;
            }
        }

        // Maksimal setiap destinasi hanya boleh memiliki 5 file galeri.
        if ($existingCount + $incomingCount > 5) {
            $sisa = 5 - $existingCount;

            return redirect()->back()->with(
                'error',
                "Maksimal 5 file galeri per wisata. Saat ini sudah ada {$existingCount} file. Anda hanya dapat mengunggah maksimal {$sisa} file lagi."
            );
        }

        // (3) Periksa jenis file dan ukuran file yang diunggah.
        foreach ($files['media'] as $file) {

            if ($file->isValid() && !$file->hasMoved()) {

                $mimeType = $file->getMimeType();
                $isImage  = strpos($mimeType, 'image/') === 0;
                $isVideo  = strpos($mimeType, 'video/') === 0;

                // Ubah ukuran file dari Byte menjadi MegaByte (MB).
                $sizeMB = $file->getSize() / (1024 * 1024);

                if ($isImage && $sizeMB > 3) {
                    return redirect()->back()->with('error', 'Gagal: Ukuran foto tidak boleh lebih dari 3MB.');
                }

                if ($isVideo && $sizeMB > 20) {
                    return redirect()->back()->with('error', 'Gagal: Ukuran video tidak boleh lebih dari 20MB.');
                }

                if (!$isImage && !$isVideo) {
                    return redirect()->back()->with('error', 'Gagal: Format file tidak didukung. Hanya Foto/Video.');
                }
            }
        }

        // (4) Simpan file ke folder server kemudian simpan informasinya ke database.
        $uploadCount = 0;

        foreach ($files['media'] as $file) {

            if ($file->isValid() && !$file->hasMoved()) {

                $mimeType = $file->getMimeType();
                $isImage  = strpos($mimeType, 'image/') === 0;
                $isVideo  = strpos($mimeType, 'video/') === 0;

                // Buat nama file baru secara acak agar tidak bentrok
                // dengan nama file milik pengguna lain.
                $newName = $file->getRandomName();

                // Pindahkan file fisik ke folder uploads/galeri.
                $file->move(FCPATH . 'uploads/galeri', $newName);

                // Simpan informasi file ke tabel database.
                $this->galeriModel->insert([
                    'destinasi_id' => $destinasi_id,                 // Kolom database => ID destinasi dari Form HTML
                    'tipe_file'    => $isImage ? 'foto' : 'video',   // Kolom database => Jenis file
                    'nama_file'    => $newName                       // Kolom database => Nama file yang tersimpan di server
                ]);

                $uploadCount++;
            }
        }

        // (5) Jika ada file yang berhasil diunggah, catat aktivitas admin.
        if ($uploadCount > 0) {

            ActivityLogService::log(
                "Mengunggah {$uploadCount} media untuk destinasi: {$destinasi['nama_wisata']}"
            );

            return redirect()->to('/admin/galeri')
                ->with('success', "Berhasil mengunggah {$uploadCount} file.");
        }

        // Jika seluruh proses gagal, tampilkan pesan kesalahan.
        return redirect()->back()->with(
            'error',
            'Gagal mengunggah file. Pastikan format file benar (Hanya Foto/Video).'
        );
    }

    /* ======================================================
       FITUR HAPUS DATA (DELETE) - function destroy()

       1. Cari data media berdasarkan ID.
       2. Hapus file fisik dari folder server.
       3. Hapus data pada database.
       4. Catat aktivitas admin.
       5. Kembali ke halaman sebelumnya.
    ====================================================== */
    public function destroy($id)
    {
        // (1) Cari data media berdasarkan ID.
        $media = $this->galeriModel->find($id);

        if ($media) {

            // Ambil nama destinasi untuk kebutuhan Activity Log.
            $destinasi  = $this->destinasiModel->find($media['destinasi_id']);
            $namaWisata = $destinasi ? $destinasi['nama_wisata'] : 'Tidak Diketahui';

            // (2) Tentukan lokasi file fisik pada server.
            $filepath = FCPATH . 'uploads/galeri/' . $media['nama_file'];

            // Jika file fisik masih ada, hapus file tersebut
            // agar tidak memenuhi penyimpanan server.
            if (file_exists($filepath)) {
                unlink($filepath);
            }

            // (3) Hapus data media dari tabel database.
            $this->galeriModel->delete($id);

            // (4) Catat aktivitas penghapusan ke Activity Log.
            ActivityLogService::log("Menghapus media galeri ID {$id} pada wisata {$namaWisata}");

            // (5) Kembalikan pengguna ke halaman sebelumnya
            // dengan membawa pesan bahwa proses berhasil.
            return redirect()->back()->with('success', 'Media berhasil dihapus.');
        }

        // Jika data media tidak ditemukan, tampilkan pesan kesalahan.
        return redirect()->back()->with('error', 'Media tidak ditemukan.');
    }
}
