<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Libraries\ActivityLogService;

/* ======================================================
   CONTROLLER PROFIL (MVC - CONTROLLER)

   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan
   database pengolahan data (Model). Jika ada pengguna yang mengklik
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function index()  = Menampilkan halaman profil admin yang sedang login.
   - function update() = Memperbarui data profil admin yang sedang login, termasuk foto profil dan password.
====================================================== */

class ProfilController extends BaseController
{
    /* ======================================================
       FITUR BACA & TAMPIL DATA (READ) - function index()

       1. Menyiapkan data halaman.
       2. Mengirim data ke halaman HTML/View.
    ====================================================== */
    public function index()
    {
        // (1) Bungkus data yang dibutuhkan halaman
        // ke dalam satu keranjang bernama $data.
        $data = [
            'title' => 'Profil Admin',
        ];

        // (2) Kembalikan (return) hasil proses ini dengan memuat (view)
        // file halaman 'admin/profil/index.php'.
        // Jangan lupa bawa keranjang $data-nya ke halaman tersebut.
        return view('admin/profil/index', $data);
    }

    /* ======================================================
       FITUR UBAH DATA (UPDATE) - function update()

       1. Ambil ID admin yang sedang login.
       2. Validasi seluruh data dari Form HTML.
       3. Siapkan data yang akan diperbarui.
       4. Jika ada, unggah foto profil baru.
       5. Jika ada, ganti password.
       6. Simpan perubahan ke database.
       7. Perbarui Session atau logout jika password berubah.
    ====================================================== */
    public function update()
    {
        // (1) Ambil ID admin yang sedang login
        // dari Session browser.
        $id = session()->get('id');

        // (2) Buat aturan validasi untuk data
        // yang dikirim dari Form HTML.
        $rules = [
            'nama'     => 'required',
            'username' => "required|is_unique[admin.username,id,{$id}]"
        ];

        // Jika admin mengisi kolom password,
        // aktifkan validasi khusus password.
        if (!empty($this->request->getPost('password'))) {
            $rules['password']         = 'min_length[5]';
            $rules['password_confirm'] = 'required|matches[password]';
        }

        // Jika validasi gagal, kembalikan ke halaman sebelumnya
        // beserta seluruh isian form dan pesan kesalahan.
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with(
                'error',
                'Validasi gagal. Pastikan konfirmasi password benar jika ingin mengganti password dan username belum digunakan.'
            );
        }

        // (3) Panggil Model Admin agar Controller
        // dapat berkomunikasi dengan tabel admin.
        $adminModel = new AdminModel();

        // Ambil seluruh data dari Form HTML kemudian
        // susun sesuai nama kolom pada tabel database.
        $data = [
            'nama'      => $this->request->getPost('nama'),       // Kolom database => Input form "nama"
            'username'  => $this->request->getPost('username'),   // Kolom database => Input form "username"
            'email'     => $this->request->getPost('email'),      // Kolom database => Input form "email"
            'whatsapp'  => $this->request->getPost('whatsapp'),   // Kolom database => Input form "whatsapp"
            'instagram' => $this->request->getPost('instagram')   // Kolom database => Input form "instagram"
        ];

        // (4) Periksa apakah admin mengunggah foto profil baru.
        $fotoFile = $this->request->getFile('foto_profil');

        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {

            // Buat nama file baru secara acak agar
            // tidak bentrok dengan file lain.
            $namaFoto = $fotoFile->getRandomName();

            // Pindahkan file fisik ke folder uploads/profil.
            $fotoFile->move('uploads/profil', $namaFoto);

            // Simpan nama file ke kolom database.
            $data['foto_profil'] = $namaFoto;

            // Cari data admin lama untuk mengetahui
            // nama file foto profil sebelumnya.
            $oldAdmin = $adminModel->find($id);

            // Jika foto lama masih ada di server,
            // hapus agar tidak memenuhi penyimpanan.
            if (!empty($oldAdmin['foto_profil']) && file_exists('uploads/profil/' . $oldAdmin['foto_profil'])) {
                unlink('uploads/profil/' . $oldAdmin['foto_profil']);
            }
        }

        // (5) Periksa apakah admin ingin mengganti password.
        $passwordChanged = false;

        if (!empty($this->request->getPost('password'))) {

            // Enkripsi password sebelum disimpan ke database.
            $data['password'] = md5($this->request->getPost('password'));

            // Tandai bahwa password telah berubah.
            $passwordChanged = true;
        }

        // (6) Simpan seluruh perubahan ke database.
        $adminModel->update($id, $data);

        // Catat aktivitas admin ke tabel Activity Log.
        ActivityLogService::log("Mengubah profil pribadi");

        // (7) Jika password berubah, demi keamanan
        // paksa admin login kembali.
        if ($passwordChanged) {
            session()->destroy();

            return redirect()->to('/admin/login')->with(
                'success',
                'Password Anda berhasil diubah. Silakan login kembali.'
            );
        }

        // Jika hanya data profil biasa yang berubah,
        // perbarui juga data Session agar nama, foto,
        // dan informasi lainnya langsung berubah di halaman.
        $sesData = [
            'nama'      => $data['nama'],
            'username'  => $data['username'],
            'email'     => $data['email'],
            'whatsapp'  => $data['whatsapp'],
            'instagram' => $data['instagram']
        ];

        // Jika foto profil ikut berubah,
        // simpan juga ke dalam Session.
        if (isset($data['foto_profil'])) {
            $sesData['foto_profil'] = $data['foto_profil'];
        }

        session()->set($sesData);

        // Kembali ke halaman profil dengan pesan berhasil.
        return redirect()->to('/admin/profil')->with(
            'success',
            'Profil Anda berhasil diperbarui.'
        );
    }
}
