<?php

namespace App\Libraries;

use App\Models\ActivityLogModel;
use CodeIgniter\Config\Services;

/* ======================================================
   LIBRARY KHUSUS: PEREKAM AKTIVITAS (ActivityLogService)
   
   Apa kegunaan file ini?
   Ibarat Mesin Pencatat Otomatis. Alih-alih menulis kodingan panjang di setiap 
   Controller saat admin melakukan hapus/edit data, kita cukup panggil 
   satu fungsi ringkas dari file ini: ActivityLogService::log("Teks").
   Library ini akan otomatis mendeteksi nama admin dan alamat IP-nya, 
   lalu menyimpannya ke database.
====================================================== */
class ActivityLogService
{
    /**
     * FUNGSI MENCATAT LOG (log)
     * 
     * @param string $activity Teks yang menjelaskan apa yang sedang dilakukan (contoh: "Ubah data")
     * @param object|array|null $actor Data admin yang bertugas (Opsional, jika kosong akan otomatis ambil dari session)
     */
    public static function log(string $activity, $actor = null)
    {
        // 1. Panggil asisten sistem untuk melihat Sesi (Session/Identitas Login)
        // dan Request (Data koneksi seperti IP address internet).
        $session = Services::session();
        $request = Services::request();

        // Siapkan variabel penampung sementara
        $userId = null;
        $userName = 'Guest'; // Anggap saja tamu jika gagal terdeteksi

        // 2. TENTUKAN SIAPA PELAKUNYA (Identifikasi Admin)
        if ($actor !== null) {
            // Jika identitas pelaku sudah diselipkan langsung ke parameter fungsi:
            if (is_object($actor)) {
                $userId = $actor->id ?? null;
                $userName = $actor->name ?? $actor->username ?? 'Guest';
            } elseif (is_array($actor)) {
                $userId = $actor['id'] ?? null;
                $userName = $actor['nama'] ?? $actor['name'] ?? $actor['username'] ?? 'Guest';
            }
        } else {
            // Jika parameter pelaku kosong, sistem akan otomatis menggeledah Session 
            // (Mengecek siapa user yang saat ini sedang login di browser).
            if ($session->get('isLoggedIn')) {
                $userId = $session->get('id'); 
                $userName = $session->get('nama') ?? $session->get('username') ?? 'Admin';
            }
        }

        // 3. RAKIT PAKET DATA LOG
        // Gabungkan semua temuan menjadi satu paket keranjang (array) yang siap disetor ke database.
        $data = [
            'user_id'    => $userId, // Nomor KTP Admin
            'name'       => $userName, // Nama Admin
            'ip_address' => $request->getIPAddress(), // IP WiFi/Jaringan Admin saat ngeklik
            'activity'   => $activity, // Teks laporannya
            'created_at' => date('Y-m-d H:i:s'), // Stempel waktu kejadian detik itu juga
        ];
        
        // 4. SIMPAN KE DATABASE
        // Suruh Model ActivityLog untuk menyimpan paket $data tersebut secara permanen.
        $logModel = new ActivityLogModel();
        return $logModel->insert($data);
    }
}
