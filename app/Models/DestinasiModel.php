<?php

namespace App\Models;

use CodeIgniter\Model;

/* ======================================================
   MODEL DESTINASI (MVC - MODEL)
   
   Apa itu Model?
   Model adalah tukang yang khusus mengurus keluar-masuknya data 
   dari aplikasi ke dalam database (MySQL). Controller akan meminta 
   tolong ke Model jika ingin menyimpan, mengedit, atau menghapus data.
   Model ini secara spesifik hanya mengurus tabel 'destinasi' di database.
====================================================== */
class DestinasiModel extends Model
{
    // 1. Konfigurasi Dasar Tabel
    protected $table            = 'destinasi'; // Nama tabel persis yang ada di dalam database phpMyAdmin
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
        'kategori_id', 'nama_wisata', 'slug', 'deskripsi', 'alamat', 
        'jam_operasional', 'hari_operasional', 'harga_tiket', 
        'aturan', 'latitude', 'longitude', 'link_gmaps', 'thumbnail', 'fasilitas'
    ];

    // 3. Konfigurasi Waktu Otomatis (Timestamps)
    // Fitur sakti CodeIgniter: Aplikasi akan otomatis mendeteksi dan mencatat waktu komputer saat ini 
    // ke dalam tabel setiap kali ada penambahan atau pengubahan data. Kita tidak perlu menulis kodingan waktu secara manual.
    protected $useTimestamps = true;         // Aktifkan fitur waktu otomatis
    protected $dateFormat    = 'datetime';   // Format waktu yang dipakai (Y-m-d H:i:s / Tahun-Bulan-Hari Jam:Menit:Detik)
    protected $createdField  = 'created_at'; // Kolom di database tempat menyimpan jejak "Waktu Dibuat"
    protected $updatedField  = 'updated_at'; // Kolom di database tempat menyimpan jejak "Terakhir Diubah"
}
