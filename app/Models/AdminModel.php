<?php

namespace App\Models;

use CodeIgniter\Model;

/* ======================================================
   MODEL ADMIN (MVC - MODEL)
   
   Apa itu Model Admin?
   Ini adalah petugas spesialis keamanan data diri karyawan.
   Dia memastikan bahwa hanya kolom yang diperbolehkan saja
   (seperti username, password, email) yang bisa disimpan ke 
   tabel 'admin'.
====================================================== */
class AdminModel extends Model
{
    // 1. Konfigurasi Database
    protected $table            = 'admin'; // Tabel penyimpanan datanya di MySQL
    protected $primaryKey       = 'id';    // ID khusus setiap admin
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Data dipanggil keluar dalam wujud Array
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // 2. Pintu Masuk Keamanan (Allowed Fields)
    // Cegah hacker menambah kolom liar. Hanya kolom-kolom ini yang boleh masuk.
    protected $allowedFields    = [
        'nama', 'username', 'password', 'foto_profil', 
        'remember_token', 'email', 'whatsapp', 'instagram'
    ];

    // 3. Pencatat Waktu Otomatis
    // Aktifkan timestamp otomatis bawaan CodeIgniter. 
    // Setiap kali ada admin baru ditambahkan atau profilnya diubah,
    // tanggal kejadiannya otomatis terisi.
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
