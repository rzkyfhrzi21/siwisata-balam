<?php

namespace App\Models;

use CodeIgniter\Model;

/* ======================================================
   MODEL DESTINASI FASILITAS (MVC - MODEL)

   Apa itu Model?
   Model adalah bagian dari aplikasi yang bertugas menghubungkan
   Controller dengan database (MySQL). Controller akan meminta
   bantuan Model jika ingin menyimpan atau mengambil data.

   Model ini secara khusus mengelola tabel 'destinasi_fasilitas',
   yaitu tabel penghubung antara data destinasi dan fasilitas.
====================================================== */

class DestinasiFasilitasModel extends Model
{
    // 1. Konfigurasi Dasar Tabel
    protected $table            = 'destinasi_fasilitas'; // Nama tabel penghubung antara destinasi dan fasilitas
    protected $primaryKey       = 'destinasi_id';        // Primary Key sementara karena tabel pivot umumnya tidak memiliki ID utama tunggal
    protected $useAutoIncrement = false;                 // Nilai ID tidak bertambah otomatis karena berasal dari tabel lain
    protected $returnType       = 'array';               // Data yang diambil dari database akan berbentuk Array
    protected $useSoftDeletes   = false;                 // Data yang dihapus akan langsung hilang dari database
    protected $protectFields    = true;                  // Melindungi tabel agar hanya kolom yang diizinkan yang dapat diisi

    // 2. Kolom Yang Diizinkan (Allowed Fields)
    // Hanya kolom berikut yang boleh diisi oleh Controller.
    // Tabel ini hanya menyimpan hubungan antara destinasi dan fasilitas.
    protected $allowedFields    = [
        'destinasi_id', // Nama kolom database yang menyimpan ID destinasi yang dipilih
        'fasilitas_id'  // Nama kolom database yang menyimpan ID fasilitas yang dipilih
    ];
}
