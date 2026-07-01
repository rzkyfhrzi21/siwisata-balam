<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriWisataModel;
use App\Libraries\ActivityLogService;

/* ======================================================
   CONTROLLER KATEGORI WISATA (MVC - CONTROLLER)

   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan
   database pengolahan data (Model). Jika ada pengguna yang mengklik
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function index()   = Menampilkan seluruh data kategori wisata.
   - function store()   = Menyimpan data kategori wisata baru ke database.
   - function update()  = Mengubah data kategori wisata yang sudah ada.
   - function destroy() = Menghapus data kategori wisata dari database.
====================================================== */

class KategoriController extends BaseController
{
    protected $kategoriModel;

    /* ======================================================
       PERSIAPAN CONTROLLER - function __construct()

       Fungsi ini akan dijalankan otomatis saat Controller dipanggil.
       Tujuannya untuk menyiapkan Model agar dapat digunakan oleh
       seluruh fungsi di dalam Controller ini.
    ====================================================== */
    public function __construct()
    {
        // Panggil Model Kategori Wisata agar Controller dapat
        // berkomunikasi dengan tabel 'kategori_wisata' di database.
        $this->kategoriModel = new KategoriWisataModel();
    }

    /* ======================================================
       FITUR BACA & TAMPIL DATA (READ) - function index()

       1. Ambil seluruh data kategori dari database.
       2. Bungkus seluruh hasil ke dalam variabel $data.
       3. Kirim dan tampilkan data tersebut ke halaman HTML/View.
    ====================================================== */
    public function index()
    {
        // Fungsi ini sebenarnya hanya sebagai cadangan.
        // Halaman utama kategori wisata sudah ditampilkan
        // melalui Controller Master Data.

        // (1) Bungkus seluruh data kategori ke dalam
        // satu keranjang/variabel bernama $data.
        $data = [
            'title'     => 'Manajemen Kategori Wisata',
            'kategori'  => $this->kategoriModel->orderBy('id', 'DESC')->findAll()
        ];

        // (2) Kembalikan (return) hasil proses ini dengan memuat (view)
        // file halaman 'admin/kategori/index.php'.
        // Jangan lupa bawa keranjang $data-nya ke halaman tersebut.
        return view('admin/kategori/index', $data);
    }

    /* ======================================================
       FITUR TAMBAH DATA (CREATE) - function store()

       1. Validasi data yang dikirim dari Form HTML.
       2. Siapkan data yang akan disimpan.
       3. Simpan data ke database.
       4. Catat aktivitas admin.
       5. Kembali ke halaman sebelumnya.
    ====================================================== */
    public function store()
    {
        // (1) Validasi data yang dikirim dari Form HTML.
        // Pastikan nama kategori tidak kosong dan belum pernah digunakan.
        $rules = [
            'nama_kategori' => 'required|is_unique[kategori_wisata.nama_kategori]'
        ];

        // Jika validasi gagal, kembalikan pengguna ke halaman sebelumnya
        // beserta seluruh isian form dan pesan kesalahan.
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: Nama kategori sudah ada atau kosong.');
        }

        // (2) Ambil data dari Form HTML kemudian
        // susun sesuai nama kolom pada tabel database.
        $nama_kategori = $this->request->getPost('nama_kategori');

        $data = [
            'nama_kategori' => $nama_kategori,                          // Kolom database => Input form "nama_kategori"
            'slug'          => url_title($nama_kategori, '-', true)     // Kolom database => URL ramah mesin pencari (SEO)
        ];

        // (3) Minta Model menyimpan data kategori baru ke database.
        $this->kategoriModel->insert($data);

        // (4) Catat aktivitas admin ke tabel Activity Log
        // agar riwayat perubahan data dapat diketahui.
        ActivityLogService::log("Menambah kategori wisata: {$nama_kategori}");

        // (5) Kembalikan pengguna ke halaman sebelumnya
        // dengan membawa pesan bahwa proses berhasil.
        return redirect()->back()->with('success', 'Kategori wisata berhasil ditambahkan.');
    }

    /* ======================================================
       FITUR UBAH DATA (UPDATE) - function update()

       1. Validasi data baru.
       2. Cari data berdasarkan ID.
       3. Siapkan data yang akan diperbarui.
       4. Simpan perubahan ke database.
       5. Catat aktivitas admin.
       6. Kembali ke halaman sebelumnya.
    ====================================================== */
    public function update($id)
    {
        // (1) Validasi data yang dikirim dari Form HTML.
        // Nama kategori harus tetap unik, kecuali milik data yang sedang diedit.
        $rules = [
            'nama_kategori' => "required|is_unique[kategori_wisata.nama_kategori,id,{$id}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: Nama kategori sudah ada atau kosong.');
        }

        // (2) Cari data kategori berdasarkan ID.
        $kategori = $this->kategoriModel->find($id);

        // Jika data tidak ditemukan, hentikan proses.
        if (!$kategori) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // (3) Ambil data terbaru dari Form HTML kemudian
        // susun sesuai nama kolom pada tabel database.
        $nama_kategori = $this->request->getPost('nama_kategori');

        $data = [
            'nama_kategori' => $nama_kategori,                          // Kolom database => Input form "nama_kategori"
            'slug'          => url_title($nama_kategori, '-', true)     // Kolom database => URL ramah mesin pencari (SEO)
        ];

        // (4) Minta Model memperbarui data lama dengan data yang baru.
        $this->kategoriModel->update($id, $data);

        // (5) Catat aktivitas admin ke tabel Activity Log.
        ActivityLogService::log("Mengubah kategori wisata: {$nama_kategori}");

        // (6) Kembalikan pengguna ke halaman sebelumnya
        // dengan membawa pesan bahwa proses berhasil.
        return redirect()->back()->with('success', 'Kategori wisata berhasil diubah.');
    }

    /* ======================================================
       FITUR HAPUS DATA (DELETE) - function destroy()

       1. Cari data berdasarkan ID.
       2. Periksa apakah data masih digunakan.
       3. Hapus data dari database.
       4. Catat aktivitas admin.
       5. Kembali ke halaman sebelumnya.
    ====================================================== */
    public function destroy($id)
    {
        // (1) Cari data kategori berdasarkan ID.
        $kategori = $this->kategoriModel->find($id);

        if ($kategori) {

            // (2) Sebelum menghapus kategori, pastikan kategori ini
            // belum digunakan oleh data destinasi wisata.
            $destinasiModel = new \App\Models\DestinasiModel();

            // Hitung jumlah destinasi yang masih menggunakan kategori ini.
            $count = $destinasiModel->where('kategori_id', $id)->countAllResults();

            // Jika masih digunakan, batalkan proses penghapusan.
            // Hal ini dilakukan agar data destinasi tidak kehilangan kategori.
            if ($count > 0) {
                return redirect()->back()->with(
                    'error',
                    "Gagal menghapus kategori. Terdapat {$count} destinasi yang menggunakan kategori ini."
                );
            }

            // (3) Minta Model menghapus data kategori dari database.
            $nama = $kategori['nama_kategori'];

            $this->kategoriModel->delete($id);

            // (4) Catat aktivitas penghapusan ke tabel Activity Log.
            ActivityLogService::log("Menghapus kategori wisata: {$nama}");

            // (5) Kembalikan pengguna ke halaman sebelumnya
            // dengan membawa pesan bahwa proses berhasil.
            return redirect()->back()->with('success', 'Kategori wisata berhasil dihapus.');
        }

        // Jika data tidak ditemukan, tampilkan pesan kesalahan.
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
}
