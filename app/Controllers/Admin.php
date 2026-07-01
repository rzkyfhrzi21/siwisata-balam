<?php

namespace App\Controllers;

/* ======================================================
   CONTROLLER ADMIN (MVC - CONTROLLER)
   
   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan
   database pengolahan data (Model). Jika ada pengguna yang mengklik
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function dashboard() = Menampilkan halaman utama dashboard (Legacy/Cadangan).
====================================================== */

class Admin extends BaseController
{
    /* ======================================================
       FITUR TAMPIL DASHBOARD (READ) - function dashboard()

       1. Memuat halaman dashboard utama admin.
    ====================================================== */
    public function dashboard()
    {
        // (1) Kembalikan (return) hasil proses ini dengan memuat (view)
        // file halaman 'admin/dashboard.php'.
        return view('admin/dashboard');
    }
}
