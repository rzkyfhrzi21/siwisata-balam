<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/* ======================================================
   CONTROLLER ERROR (MVC - CONTROLLER)
   
   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan 
   database pengolahan data (Model). Jika ada pengguna yang mengklik 
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function error404() = Menampilkan halaman ketika URL atau halaman yang diminta tidak ditemukan.
   - function error500() = Menampilkan halaman ketika terjadi kesalahan pada server atau sistem.
====================================================== */

class ErrorController extends BaseController
{
    /* ======================================================
       FITUR TAMPIL HALAMAN ERROR 404 - function error404()

       1. Memuat halaman error 404.
       2. Menampilkan informasi bahwa halaman yang diminta tidak ditemukan.
    ====================================================== */
    public function error404()
    {
        // Kembalikan (return) hasil proses ini dengan memuat (view)
        // file halaman 'admin/error/404.php'.
        return view('admin/error/404');
    }

    /* ======================================================
       FITUR TAMPIL HALAMAN ERROR 500 - function error500()

       1. Memuat halaman error 500.
       2. Menampilkan informasi bahwa sedang terjadi kesalahan pada server.
    ====================================================== */
    public function error500()
    {
        // Kembalikan (return) hasil proses ini dengan memuat (view)
        // file halaman 'admin/error/500.php'.
        return view('admin/error/500');
    }
}
