<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FasilitasModel;
use App\Libraries\ActivityLogService;

/* ======================================================
   CONTROLLER FASILITAS WISATA (MVC - CONTROLLER)
   
   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan 
   database pengolahan data (Model). Jika ada pengguna yang mengklik 
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function store()   = Menyimpan data fasilitas baru ke database.
   - function update()  = Mengubah data fasilitas yang sudah ada.
   - function destroy() = Menghapus data fasilitas dari database.
====================================================== */

class FasilitasController extends BaseController
{
    protected $fasilitasModel;

    /* ======================================================
       PERSIAPAN CONTROLLER - function __construct()

       Fungsi ini akan dijalankan otomatis saat Controller dipanggil.
       Tujuannya untuk menyiapkan Model agar dapat digunakan oleh
       seluruh fungsi di dalam Controller ini.
    ====================================================== */
    public function __construct()
    {
        // Panggil Model Fasilitas agar Controller dapat
        // berkomunikasi dengan tabel 'fasilitas' di database.
        $this->fasilitasModel = new FasilitasModel();
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
        // Pastikan nama fasilitas tidak kosong dan belum pernah digunakan.
        $rules = [
            'nama_fasilitas' => 'required|is_unique[fasilitas.nama_fasilitas]'
        ];

        // Jika validasi gagal, kembalikan pengguna ke halaman sebelumnya
        // beserta seluruh isian form dan pesan kesalahan.
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: Nama fasilitas harus unik dan tidak boleh kosong.');
        }

        // (2) Ambil seluruh data dari Form HTML kemudian
        // susun sesuai nama kolom pada tabel database.
        $nama = $this->request->getPost('nama_fasilitas');

        $data = [
            'nama_fasilitas' => $nama,                                   // Kolom database => Input form "nama_fasilitas"
            'icon'           => $this->request->getPost('icon'),         // Kolom database => Input form "icon"
            'slug'           => url_title($nama, '-', true)              // Kolom database => URL ramah mesin pencari (SEO)
        ];

        // (3) Minta Model menyimpan data fasilitas baru ke database.
        $this->fasilitasModel->insert($data);

        // (4) Catat aktivitas admin ke tabel Activity Log
        // agar riwayat perubahan data dapat diketahui.
        ActivityLogService::log("Menambah fasilitas wisata: {$data['nama_fasilitas']}");

        // (5) Kembalikan pengguna ke halaman sebelumnya
        // dengan membawa pesan bahwa proses berhasil.
        return redirect()->back()->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    /* ======================================================
       FITUR UBAH DATA (UPDATE) - function update()

       1. Cari data berdasarkan ID.
       2. Validasi data baru.
       3. Siapkan data yang akan diperbarui.
       4. Simpan perubahan ke database.
       5. Catat aktivitas admin.
       6. Kembali ke halaman sebelumnya.
    ====================================================== */
    public function update($id)
    {
        // (1) Cari data fasilitas berdasarkan ID.
        $fasilitas = $this->fasilitasModel->find($id);

        // Jika data tidak ditemukan, hentikan proses.
        if (!$fasilitas) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // (2) Validasi data baru.
        // Nama fasilitas harus tetap unik, kecuali milik data yang sedang diedit.
        $rules = [
            'nama_fasilitas' => "required|is_unique[fasilitas.nama_fasilitas,id,{$id}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: Nama fasilitas harus unik dan tidak boleh kosong.');
        }

        // (3) Ambil data terbaru dari Form HTML kemudian
        // susun sesuai nama kolom pada tabel database.
        $nama = $this->request->getPost('nama_fasilitas');

        $data = [
            'nama_fasilitas' => $nama,                                   // Kolom database => Input form "nama_fasilitas"
            'icon'           => $this->request->getPost('icon'),         // Kolom database => Input form "icon"
            'slug'           => url_title($nama, '-', true)              // Kolom database => URL ramah mesin pencari (SEO)
        ];

        // (4) Minta Model memperbarui data lama dengan data yang baru.
        $this->fasilitasModel->update($id, $data);

        // (5) Catat aktivitas admin ke tabel Activity Log.
        ActivityLogService::log("Mengubah fasilitas wisata: {$data['nama_fasilitas']}");

        // (6) Kembalikan pengguna ke halaman sebelumnya
        // dengan membawa pesan bahwa proses berhasil.
        return redirect()->back()->with('success', 'Fasilitas berhasil diupdate.');
    }

    /* ======================================================
       FITUR HAPUS DATA (DELETE) - function destroy()

       1. Cari data berdasarkan ID.
       2. Hapus data dari database.
       3. Catat aktivitas admin.
       4. Kembali ke halaman sebelumnya.
    ====================================================== */
    public function destroy($id)
    {
        // (1) Cari data fasilitas berdasarkan ID.
        $fasilitas = $this->fasilitasModel->find($id);

        if ($fasilitas) {
            $nama = $fasilitas['nama_fasilitas'];

            // (2) Minta Model menghapus data dari database.
            // Jika database menggunakan CASCADE ON DELETE,
            // maka relasi fasilitas pada destinasi juga akan ikut terhapus.
            $this->fasilitasModel->delete($id);

            // (3) Catat aktivitas penghapusan ke tabel Activity Log.
            ActivityLogService::log("Menghapus fasilitas wisata: {$nama}");

            // (4) Kembalikan pengguna ke halaman sebelumnya
            // dengan membawa pesan bahwa proses berhasil.
            return redirect()->back()->with('success', 'Fasilitas berhasil dihapus.');
        }

        // Jika data tidak ditemukan, tampilkan pesan kesalahan.
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
}
