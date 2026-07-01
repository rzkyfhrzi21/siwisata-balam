<?php

namespace App\Models;

use CodeIgniter\Model;

/* ======================================================
   MODEL FASILITAS (MVC - MODEL)
   
   Apa itu Model?
   Model adalah tukang yang khusus mengurus keluar-masuknya data 
   dari aplikasi ke dalam database (MySQL). Controller akan meminta 
   tolong ke Model jika ingin menyimpan, mengedit, atau menghapus data.
   Model ini secara spesifik hanya mengurus tabel 'fasilitas' di database.
====================================================== */

class FasilitasModel extends Model
{
    // 1. Konfigurasi Dasar Tabel
    protected $table            = 'fasilitas'; // Nama tabel persis yang ada di dalam database phpMyAdmin
    protected $primaryKey       = 'id';        // Kolom Primary Key (kolom ID unik utama yang jadi patokan data)
    protected $useAutoIncrement = true;        // Artinya angka ID akan bertambah sendiri secara otomatis (1, 2, 3...)
    protected $returnType       = 'array';     // Saat data ditarik dari database, bentuknya akan berupa Array (kumpulan kotak data)
    protected $useSoftDeletes   = false;       // Jika true, data tidak benar-benar terhapus (hanya ditandai 'terhapus' tapi fisik datanya masih ada di database). Kita pakai false (langsung musnah).
    protected $protectFields    = true;        // Melindungi tabel agar tidak sembarangan diisi oleh penyusup.

    // 2. Kolom Yang Diizinkan (Allowed Fields)
    // PERHATIAN: Ini adalah daftar satpam penjaga pintu kolom database!
    // Hanya kolom-kolom yang tertulis di bawah ini yang BOLEH diisi/disisipkan data oleh Controller.
    // Jika ada kolom database yang luput ditulis di sini, maka datanya tidak akan pernah tersimpan!
    protected $allowedFields    = [
        'nama_fasilitas', // Nama kolom database yang menyimpan nama fasilitas wisata
        'icon',           // Nama kolom database yang menyimpan ikon fasilitas (contoh: Font Awesome)
        'slug'            // Nama kolom database yang menyimpan URL versi ramah mesin pencari (SEO)
    ];
}
