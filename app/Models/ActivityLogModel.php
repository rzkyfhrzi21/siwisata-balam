<?php

namespace App\Models;

use CodeIgniter\Model;

/* ======================================================
   MODEL ACTIVITY LOG (MVC - MODEL)
   
   Apa itu Model?
   Ibarat koki/satpam gudang, file ini adalah satu-satunya komponen 
   yang punya akses untuk masuk ke tabel 'activity_logs' di database.
   Dia mengatur kolom mana saja yang boleh diisi catatan baru.
====================================================== */
class ActivityLogModel extends Model
{
    // 1. Konfigurasi Dasar Tabel
    protected $table            = 'activity_logs'; // Nama asli tabel di dalam database phpMyAdmin
    protected $primaryKey       = 'id';            // Kolom patokan utama data (ID)
    protected $useAutoIncrement = true;            // ID akan bertambah otomatis (1, 2, 3)
    protected $returnType       = 'object';        // Data ditarik dalam bentuk Objek (bukan Array)
    protected $useSoftDeletes   = false;           // Hapus permanen
    protected $protectFields    = true;            // Aktifkan perlindungan tabel

    // 2. Daftar Satpam Kolom (Allowed Fields)
    // Hanya kolom di bawah ini yang BOLEH diisi dengan teks/catatan baru.
    // Jika ada kolom lain yang mau ditambahkan, wajib didaftarkan di sini dulu.
    protected $allowedFields    = [
        'user_id',    // Nomor ID si admin yang melakukan aktivitas
        'name',       // Nama admin tersebut
        'ip_address', // Alamat IP internet milik admin
        'activity',   // Teks catatan (contoh: "Menghapus destinasi A")
        'created_at'  // Tanggal & waktu kejadian
    ];

    // 3. Matikan Waktu Otomatis Bawaan
    // Kita matikan (false) waktu otomatis CodeIgniter, karena khusus log ini
    // kita ingin mengisi jam kejadiannya secara manual (custom) melalui Library Service.
    protected $useTimestamps = false; 
}
