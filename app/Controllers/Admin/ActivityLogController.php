<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

/* ======================================================
   CONTROLLER ACTIVITY LOG (MVC - CONTROLLER)
   
   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan 
   database pengolahan data (Model). Jika ada pengguna yang mengklik 
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function index() = Mengambil seluruh data riwayat aktivitas admin dari database lalu menampilkannya ke layar.
====================================================== */

class ActivityLogController extends BaseController
{
    /* ======================================================
       FITUR BACA & TAMPIL DATA (READ) - function index()
       1. Ambil seluruh data riwayat aktivitas dari database.
       2. Susun data ke dalam variabel $data.
       3. Kirim dan tampilkan data tersebut ke halaman HTML/View.
    ====================================================== */
    public function index()
    {
        // (1) Panggil Model Activity Log agar kita bisa berkomunikasi dengan tabel 'activity_log' di database.
        $logModel = new ActivityLogModel();

        // Ambil seluruh data riwayat aktivitas dari database.
        // 'DESC' artinya data terbaru (waktu paling akhir) akan tampil paling atas.
        $logs = $logModel->orderBy('created_at', 'DESC')->findAll();

        // (2) Bungkus semua data hasil tarikan database tadi ke dalam satu keranjang/variabel bernama $data.
        // Variabel $data inilah yang akan dikirim ke halaman layar (View).
        $data = [
            'title' => 'Activity Log', // Hanya judul halaman
            'logs'  => $logs           // Berisi kumpulan data riwayat aktivitas admin
        ];

        // (3) Kembalikan (return) hasil proses ini dengan memuat (view) file halaman 'admin/activity_log/index.php'.
        // Jangan lupa bawa keranjang $data-nya ke halaman tersebut.
        return view('admin/activity_log/index', $data);
    }
}
