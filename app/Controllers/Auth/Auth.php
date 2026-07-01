<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Libraries\ActivityLogService;

/* ======================================================
   CONTROLLER OTENTIKASI / LOGIN (MVC - CONTROLLER)
   
   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan
   database pengolahan data (Model). Jika ada pengguna yang mengklik
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function login()        = Menampilkan halaman form login.
   - function processLogin() = Memeriksa kebenaran username dan password, lalu membuat sesi (Session) login.
   - function logout()       = Menghapus sesi login dan mengeluarkan admin dari sistem.
====================================================== */

class Auth extends BaseController
{
    /* ======================================================
       FITUR TAMPIL HALAMAN LOGIN - function login()

       1. Periksa apakah admin sudah dalam keadaan login.
       2. Tampilkan halaman login jika belum.
    ====================================================== */
    public function login()
    {
        // (1) Periksa memori browser (Session).
        // Jika admin ternyata sudah login sebelumnya, langsung 
        // arahkan (redirect) dia ke halaman Dashboard agar tidak perlu login lagi.
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }
        
        // (2) Jika belum login, tampilkan halaman form login.
        return view('auth/login');
    }

    /* ======================================================
       FITUR PROSES LOGIN - function processLogin()

       1. Ambil username dan password dari Form HTML.
       2. Cari data admin berdasarkan username di database.
       3. Cocokkan password yang diketik dengan yang ada di database.
       4. Jika cocok, buat memori sesi (Session) login.
       5. Jika dicentang, buat Cookie untuk fitur "Ingat Saya" (Remember Me).
       6. Catat aktivitas login.
       7. Arahkan ke Dashboard.
    ====================================================== */
    public function processLogin()
    {
        // Panggil sistem sesi dan Model Admin.
        $session    = session();
        $adminModel = new AdminModel();
        
        // (1) Ambil ketikan dari Form HTML.
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // (2) Cari tahu apakah username tersebut terdaftar di tabel admin.
        $admin = $adminModel->where('username', $username)->first();
        
        // Jika akun admin ditemukan:
        if ($admin) {
            
            // (3) Cocokkan password yang diketik.
            // Catatan: Password disamarkan menggunakan rumus MD5 agar aman.
            if (md5($password) === $admin['password']) {
                
                // (4) Jika benar, buat memori sesi (Session) di browser
                // agar sistem terus mengingat siapa yang sedang login.
                $ses_data = [
                    'id'         => $admin['id'],                   // Kolom database => ID admin
                    'nama'       => $admin['nama'],                 // Kolom database => Nama lengkap
                    'username'   => $admin['username'],             // Kolom database => Nama pengguna
                    'email'      => $admin['email'] ?? '',          // Kolom database => Alamat email
                    'whatsapp'   => $admin['whatsapp'] ?? '',       // Kolom database => Nomor WA
                    'instagram'  => $admin['instagram'] ?? '',      // Kolom database => Akun Instagram
                    'foto_profil'=> $admin['foto_profil'],          // Kolom database => Nama file foto
                    'isLoggedIn' => TRUE                            // Tanda bahwa dia sudah login
                ];
                
                // Simpan data tersebut ke dalam Session.
                $session->set($ses_data);
                
                // (5) Periksa apakah kotak centang "Ingat Saya" diklik.
                // Jika iya, sistem akan menyimpan Cookie agar besok-besok
                // admin tidak perlu login ulang saat membuka web.
                if ($this->request->getPost('remember')) {
                    
                    helper('text');
                    
                    // Buat kode token rahasia secara acak.
                    $token = random_string('alnum', 64);
                    
                    // Simpan kode token tersebut ke database.
                    $adminModel->update($admin['id'], ['remember_token' => $token]);
                    
                    // Buat file Cookie di browser admin yang akan 
                    // bertahan selama 30 hari (30 hari x 24 jam x 60 mnt x 60 dtk).
                    helper('cookie');
                    set_cookie('remember_admin', $token, 30 * 24 * 60 * 60);
                }
                
                // (6) Catat aktivitas login ke tabel Activity Log.
                ActivityLogService::log("Admin login ke sistem");
                
                // (7) Arahkan (redirect) admin yang berhasil login ke halaman Dashboard.
                return redirect()->to('/admin/dashboard');
                
            } else {
                
                // Jika password salah, kembalikan ke halaman login dan tampilkan pesan error.
                $session->setFlashdata('error', 'Password salah!');
                return redirect()->back();
            }
            
        } else {
            
            // Jika username tidak ditemukan, kembalikan ke halaman login dan tampilkan pesan error.
            $session->setFlashdata('error', 'Username tidak ditemukan!');
            return redirect()->back();
        }
    }

    /* ======================================================
       FITUR PROSES LOGOUT - function logout()

       1. Hapus fitur "Ingat Saya" (jika ada).
       2. Catat aktivitas logout.
       3. Hancurkan seluruh memori sesi (Session).
       4. Kembalikan ke halaman form login.
    ====================================================== */
    public function logout()
    {
        $session = session();
        
        // Pastikan admin memang sedang login.
        if ($session->get('isLoggedIn')) {
            
            // (2) Catat aktivitas logout ke tabel Activity Log.
            ActivityLogService::log("Admin logout dari sistem");
            
            // (1) Hapus kode token dari database.
            $adminModel = new \App\Models\AdminModel();
            $adminModel->update($session->get('id'), ['remember_token' => null]);
            
            // Hapus juga file Cookie "Ingat Saya" dari browser admin.
            helper('cookie');
            delete_cookie('remember_admin');
        }
        
        // (3) Hancurkan seluruh data memori sesi.
        $session->destroy();
        
        // (4) Arahkan (redirect) admin kembali ke halaman login.
        return redirect()->to('/admin/login');
    }
}
