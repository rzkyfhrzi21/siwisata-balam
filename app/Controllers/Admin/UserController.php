<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Libraries\ActivityLogService;

/* ======================================================
   CONTROLLER MANAJEMEN ADMIN (MVC - CONTROLLER)
   
   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan
   database pengolahan data (Model). Jika ada pengguna yang mengklik
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function index()   = Menampilkan daftar seluruh admin dan menghitung keaktifannya.
   - function store()   = Mendaftarkan admin baru beserta foto profilnya ke database.
   - function update()  = Mengubah data diri atau password admin yang sudah ada.
   - function destroy() = Menghapus akun admin secara permanen (kecuali akun sendiri).
====================================================== */

class UserController extends BaseController
{
    protected $adminModel;

    /* ======================================================
       PERSIAPAN CONTROLLER - function __construct()

       Fungsi ini akan dijalankan otomatis saat Controller dipanggil.
       Tujuannya untuk menyiapkan Model agar dapat digunakan oleh
       seluruh fungsi di dalam Controller ini.
    ====================================================== */
    public function __construct()
    {
        // Panggil Model Admin agar Controller dapat
        // berkomunikasi dengan tabel 'admin' di database.
        $this->adminModel = new AdminModel();
    }

    /* ======================================================
       FITUR BACA & TAMPIL DATA (READ) - function index()

       1. Ambil seluruh data admin dari database.
       2. Hitung statistik keaktifan tiap admin.
       3. Bungkus seluruh hasil ke dalam variabel $data.
       4. Kirim dan tampilkan data tersebut ke halaman HTML/View.
    ====================================================== */
    public function index()
    {
        // (1) Ambil seluruh data admin dari database.
        $users    = $this->adminModel->findAll();
        $logModel = new \App\Models\ActivityLogModel();
        
        // (2) Hitung tingkat keaktifan tiap admin berdasarkan log.
        $userActivity = [];

        foreach ($users as &$u) {

            // Hitung jumlah aktivitas di tabel log milik admin ini.
            $count = $logModel->where('user_id', $u['id'])->countAllResults();

            $u['activity_count'] = $count;
            
            // Jika admin memiliki aktivitas, masukkan ke dalam data grafik.
            if ($count > 0) {
                $userActivity[] = [
                    'nama'  => $u['nama'],
                    'count' => $count
                ];
            }
        }

        // (3) Bungkus seluruh data ke dalam
        // satu keranjang/variabel bernama $data.
        $data = [
            'title'        => 'Manajemen Admin',
            'users'        => $users,
            'userActivity' => $userActivity
        ];
        
        // (4) Kembalikan (return) hasil proses ini dengan memuat (view)
        // file halaman 'admin/users/index.php'.
        return view('admin/users/index', $data);
    }

    /* ======================================================
       FITUR TAMBAH DATA (CREATE) - function store()

       1. Validasi data yang dikirim dari Form HTML.
       2. Siapkan data yang akan disimpan.
       3. Jika ada, unggah foto profil ke server.
       4. Simpan data admin ke database.
       5. Catat aktivitas ke log.
       6. Kembali ke halaman sebelumnya.
    ====================================================== */
    public function store()
    {
        // (1) Validasi data yang dikirim dari Form HTML.
        $rules = [
            'nama'             => 'required',                                // Nama wajib diisi
            'username'         => 'required|is_unique[admin.username]',      // Username dilarang kembar
            'password'         => 'required|min_length[5]',                  // Minimal 5 karakter
            'password_confirm' => 'required|matches[password]'               // Harus sama dengan password
        ];

        // Jika validasi gagal, kembalikan dengan pesan error.
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with(
                'error', 
                'Validasi gagal, pastikan password cocok dan username belum digunakan.'
            );
        }

        // (2) Ambil data dari Form HTML kemudian
        // susun sesuai nama kolom pada tabel database.
        $data = [
            'nama'      => $this->request->getPost('nama'),             // Kolom database => Input form "nama"
            'username'  => $this->request->getPost('username'),         // Kolom database => Input form "username"
            'password'  => md5($this->request->getPost('password')),    // Kolom database => Enkripsi password dengan MD5
            'email'     => $this->request->getPost('email'),            // Kolom database => Input form "email"
            'whatsapp'  => $this->request->getPost('whatsapp'),         // Kolom database => Input form "whatsapp"
            'instagram' => $this->request->getPost('instagram')         // Kolom database => Input form "instagram"
        ];

        // (3) Periksa apakah ada file foto profil yang diunggah.
        $fotoFile = $this->request->getFile('foto_profil');

        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            
            // Beri nama acak pada file.
            $namaFoto = $fotoFile->getRandomName();
            
            // Pindahkan file ke folder server.
            $fotoFile->move('uploads/profil', $namaFoto);
            
            // Simpan nama file ke data array.
            $data['foto_profil'] = $namaFoto;
        }

        // (4) Minta Model menyimpan data admin baru ke database.
        $this->adminModel->insert($data);

        // (5) Catat aktivitas ke log sistem.
        ActivityLogService::log("Menambah admin baru: {$data['username']}");

        // (6) Kembali ke halaman daftar admin dengan pesan berhasil.
        return redirect()->to('/admin/users')->with('success', 'Admin berhasil ditambahkan.');
    }

    /* ======================================================
       FITUR UBAH DATA (UPDATE) - function update()

       1. Validasi data baru dari form.
       2. Cari akun admin berdasarkan ID.
       3. Siapkan pembaruan data.
       4. Proses penggantian foto profil jika ada.
       5. Proses penggantian password jika diisi.
       6. Simpan perubahan ke database.
       7. Logout otomatis jika mengganti password sendiri.
    ====================================================== */
    public function update($id)
    {
        // (1) Validasi data baru. Username harus tetap unik kecuali untuk akun ini.
        $rules = [
            'nama'     => 'required',
            'username' => "required|is_unique[admin.username,id,{$id}]",
        ];

        // Tambahkan aturan khusus jika admin mengisi password baru.
        if (!empty($this->request->getPost('password'))) {
            $rules['password']         = 'min_length[5]';
            $rules['password_confirm'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with(
                'error', 
                'Validasi gagal. Pastikan konfirmasi password benar jika ingin mengganti password.'
            );
        }

        // (2) Cari data admin berdasarkan ID di database.
        $admin = $this->adminModel->find($id);

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin tidak ditemukan.');
        }

        // (3) Siapkan array data terbaru.
        $data = [
            'nama'      => $this->request->getPost('nama'),             // Kolom database => Input form "nama"
            'username'  => $this->request->getPost('username'),         // Kolom database => Input form "username"
            'email'     => $this->request->getPost('email'),            // Kolom database => Input form "email"
            'whatsapp'  => $this->request->getPost('whatsapp'),         // Kolom database => Input form "whatsapp"
            'instagram' => $this->request->getPost('instagram')         // Kolom database => Input form "instagram"
        ];

        // (4) Proses foto profil baru jika ada.
        $fotoFile = $this->request->getFile('foto_profil');

        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            
            // Ganti nama file dan pindahkan ke server.
            $namaFoto = $fotoFile->getRandomName();
            $fotoFile->move('uploads/profil', $namaFoto);
            $data['foto_profil'] = $namaFoto;
            
            // Hapus foto lama agar penyimpanan server tidak penuh.
            if (!empty($admin['foto_profil']) && file_exists('uploads/profil/' . $admin['foto_profil'])) {
                unlink('uploads/profil/' . $admin['foto_profil']);
            }
        }

        // (5) Proses penggantian password jika kolom password diisi.
        $passwordChanged = false;

        if (!empty($this->request->getPost('password'))) {
            $data['password'] = md5($this->request->getPost('password'));
            $passwordChanged  = true;
        }

        // (6) Simpan seluruh perubahan ke tabel database.
        $this->adminModel->update($id, $data);
        
        ActivityLogService::log("Mengubah data admin: {$data['username']}");

        // (7) Jika akun ini milik admin yang sedang login dan ia mengganti passwordnya,
        // hancurkan sesi (logout paksa) demi keamanan.
        if ($passwordChanged && session()->get('id') == $id) {
            session()->destroy();
            return redirect()->to('/admin/login')->with(
                'success', 
                'Password Anda berhasil diubah. Silakan login kembali.'
            );
        }

        return redirect()->to('/admin/users')->with('success', 'Admin berhasil diubah.');
    }

    /* ======================================================
       FITUR HAPUS DATA (DELETE) - function destroy()

       1. Cegah admin menghapus dirinya sendiri.
       2. Cari data admin berdasarkan ID.
       3. Hapus foto fisiknya dari server.
       4. Hapus data dari database.
       5. Catat ke log aktivitas.
    ====================================================== */
    public function destroy($id)
    {
        // (1) Sistem akan menolak jika admin mencoba bunuh diri 
        // (menghapus akunnya sendiri yang sedang aktif dipakai login).
        if (session()->get('id') == $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // (2) Cari data admin.
        $admin = $this->adminModel->find($id);

        if ($admin) {
            
            // (3) Hapus file foto dari server.
            if (!empty($admin['foto_profil']) && file_exists('uploads/profil/' . $admin['foto_profil'])) {
                unlink('uploads/profil/' . $admin['foto_profil']);
            }
            
            // (4) Hapus data dari tabel database.
            $this->adminModel->delete($id);
            
            // (5) Catat aktivitas.
            ActivityLogService::log("Menghapus admin: {$admin['username']}");

            return redirect()->back()->with('success', 'Admin berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Admin tidak ditemukan.');
    }
}
