<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriWisataModel;
use App\Models\FasilitasModel;

/* ======================================================
   CONTROLLER MASTER DATA (MVC - CONTROLLER)

   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan
   database pengolahan data (Model). Jika ada pengguna yang mengklik
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function index() = Mengambil data kategori dan fasilitas, menghitung statistik penggunaannya, lalu menampilkannya dalam satu halaman Master Data.
====================================================== */

class MasterDataController extends BaseController
{
    /* ======================================================
       FITUR BACA & TAMPIL DATA (READ) - function index()

       1. Ambil seluruh data kategori, fasilitas, dan destinasi.
       2. Hitung jumlah penggunaan setiap kategori.
       3. Hitung jumlah penggunaan setiap fasilitas.
       4. Bungkus seluruh hasil ke dalam variabel $data.
       5. Kirim dan tampilkan data tersebut ke halaman HTML/View.
    ====================================================== */
    public function index()
    {
        // (1) Panggil seluruh Model agar Controller dapat berkomunikasi
        // dengan tabel kategori, fasilitas, dan destinasi di database.
        $kategoriModel  = new KategoriWisataModel();
        $fasilitasModel = new FasilitasModel();
        $destinasiModel = new \App\Models\DestinasiModel();

        // Ambil seluruh data dari database.
        $kategoriData  = $kategoriModel->orderBy('id', 'DESC')->findAll();
        $fasilitasData = $fasilitasModel->orderBy('id', 'DESC')->findAll();
        $allDestinasi  = $destinasiModel->findAll();

        // (2) Hitung berapa banyak destinasi yang menggunakan
        // setiap kategori wisata.
        foreach ($kategoriData as &$kat) {

            // Mulai perhitungan dari angka nol.
            $kat['jumlah_wisata'] = 0;

            // Periksa seluruh data destinasi satu per satu.
            foreach ($allDestinasi as $dest) {

                // Jika ID kategori pada destinasi sama dengan
                // kategori yang sedang diperiksa, tambahkan jumlahnya.
                if ($dest['kategori_id'] == $kat['id']) {
                    $kat['jumlah_wisata']++;
                }
            }
        }

        // (3) Hitung berapa banyak destinasi yang menggunakan
        // setiap fasilitas.
        foreach ($fasilitasData as &$fas) {

            // Mulai perhitungan dari angka nol.
            $fas['jumlah_wisata'] = 0;

            // Periksa seluruh data destinasi satu per satu.
            foreach ($allDestinasi as $dest) {

                // Kolom "fasilitas" pada database disimpan dalam format JSON,
                // sehingga perlu diubah terlebih dahulu menjadi Array PHP.
                $fasIds = json_decode($dest['fasilitas'] ?? '[]', true) ?: [];

                // Jika ID fasilitas ditemukan pada destinasi tersebut,
                // tambahkan jumlah penggunaannya.
                if (in_array($fas['id'], $fasIds)) {
                    $fas['jumlah_wisata']++;
                }
            }
        }

        // (4) Bungkus seluruh hasil pengolahan tadi ke dalam
        // satu keranjang/variabel bernama $data.
        $data = [
            'title'     => 'Master Data (Kategori & Fasilitas)',
            'kategori'  => $kategoriData,
            'fasilitas' => $fasilitasData,
        ];

        // (5) Kembalikan (return) hasil proses ini dengan memuat (view)
        // file halaman 'admin/master_data/index.php'.
        // Jangan lupa bawa keranjang $data-nya ke halaman tersebut.
        return view('admin/master_data/index', $data);
    }
}
