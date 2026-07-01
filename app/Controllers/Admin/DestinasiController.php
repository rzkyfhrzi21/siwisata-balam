<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DestinasiModel;
use App\Models\KategoriWisataModel;
use App\Libraries\ActivityLogService;

/* ======================================================
   CONTROLLER DESTINASI (MVC - CONTROLLER)
   
   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan 
   database pengolahan data (Model). Jika ada pengguna yang mengklik 
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function index()   = Mengambil data destinasi dari database dan menampilkannya ke layar admin.
   - function store()   = Menerima ketikan/inputan data wisata baru dari form admin lalu menyimpannya ke database.
   - function update()  = Menerima inputan perubahan data dari admin, lalu menimpa data lama di database.
   - function destroy() = Menghapus data wisata dari database, sekaligus menghapus file foto fisiknya dari folder server.
====================================================== */
class DestinasiController extends BaseController
{
    // Deklarasi variabel penampung Model agar bisa dipakai di seluruh fungsi di file ini
    protected $destinasiModel;
    protected $kategoriModel;

    public function __construct()
    {
        // Saat Controller ini dipanggil, kita inisialisasi (siapkan) Model Destinasi dan Model Kategori
        // agar kita bisa langsung berkomunikasi dengan tabel 'destinasi' dan tabel 'kategori_wisata' di database.
        $this->destinasiModel = new DestinasiModel();
        $this->kategoriModel = new KategoriWisataModel();
    }

    /* ======================================================
       FITUR BACA & TAMPIL DATA (READ) - function index()
       1. Ambil seluruh data destinasi dari database.
       2. Ambil data kategori & fasilitas untuk keperluan dropdown pilihan.
       3. Kirim dan tampilkan data tersebut ke halaman HTML/View.
    ====================================================== */
    public function index()
    {
        // Panggil model fasilitas karena kita butuh data fasilitas wisata
        $fasilitasModel = new \App\Models\FasilitasModel();
        
        // (1) Ambil seluruh baris data dari tabel 'destinasi'. 
        // Kita gunakan 'join' agar kolom 'nama_kategori' dari tabel 'kategori_wisata' ikut terbawa.
        // 'DESC' artinya data terbaru (ID terbesar) akan tampil paling atas.
        $destinasi = $this->destinasiModel->select('destinasi.*, kategori_wisata.nama_kategori')
            ->join('kategori_wisata', 'kategori_wisata.id = destinasi.kategori_id', 'left')
            ->orderBy('destinasi.id', 'DESC')
            ->findAll();

        // Di database, fasilitas disimpan dalam bentuk teks koding JSON (seperti: ["1","2"]).
        // Proses di bawah ini (json_decode) mengubah teks tersebut menjadi bentuk susunan array biasa 
        // agar layar tampilan (View) mudah membaca dan mencentangnya.
        foreach ($destinasi as &$d) {
            $fasilitas_ids = [];
            if (!empty($d['fasilitas'])) {
                $decoded = json_decode($d['fasilitas'], true);
                if (is_array($decoded)) {
                    $fasilitas_ids = $decoded;
                }
            }
            $d['fasilitas_ids'] = $fasilitas_ids;
        }

        // (2) Bungkus semua data hasil tarikan database tadi ke dalam satu keranjang/variabel bernama $data.
        // Variabel $data inilah yang akan dikirim ke halaman layar (View).
        $data = [
            'title'          => 'Manajemen Destinasi Wisata', // Hanya judul halaman
            'destinasi'      => $destinasi,                   // Berisi kumpulan data destinasi hasil query di atas
            'kategori'       => $this->kategoriModel->findAll(), // Berisi kumpulan data kategori
            'fasilitas_list' => $fasilitasModel->findAll()       // Berisi kumpulan data fasilitas
        ];
        
        // (3) Kembalikan (return) hasil proses ini dengan memuat (view) file halaman 'admin/destinasi/index.php'.
        // Jangan lupa bawa keranjang $data-nya ke halaman tersebut.
        return view('admin/destinasi/index', $data);
    }

    /* ======================================================
       FITUR TAMBAH DATA (CREATE) - function store()
       1. Validasi (periksa) kelengkapan ketikan/inputan admin.
       2. Ambil file foto, ganti namanya, lalu simpan ke folder server.
       3. Rapikan semua inputan, lalu simpan (insert) ke tabel database.
    ====================================================== */
    public function store()
    {
        // (1) CEK VALIDASI (Pemeriksaan)
        // Kita beri aturan ketat: 'nama_wisata' wajib diisi (required) dan namanya tidak boleh kembar (is_unique) di tabel destinasi.
        $rules = [
            'nama_wisata' => 'required|is_unique[destinasi.nama_wisata]',
            'kategori_id' => 'required',
            'alamat'      => 'required',
            'thumbnail'   => 'max_size[thumbnail,2048]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]'
            // Aturan thumbnail: maksimal ukuran 2MB (2048 KB), harus berupa gambar, dan ekstensinya wajib jpg/jpeg/png.
        ];

        // Jika form isian tidak memenuhi aturan (tidak valid), maka tolak!
        // Kembalikan pengguna ke halaman sebelumnya (back), kembalikan juga ketikannya (withInput),
        // lalu munculkan pesan error/gagal.
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: Nama destinasi mungkin sudah ada atau input tidak lengkap.');
        }

        // (2) PROSES UPLOAD FOTO
        // Ambil file fisik foto yang diupload dari form (dengan nama atribut form 'thumbnail')
        $fotoFile = $this->request->getFile('thumbnail');
        $namaFoto = null;

        // Jika fotonya ada, valid, dan belum dipindahkan...
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            // Beri nama acak pada foto agar tidak bentrok (misal dari "pemandangan.jpg" jadi "163abc899.jpg")
            $namaFoto = $fotoFile->getRandomName(); 
            // Pindahkan file foto fisik tersebut secara permanen ke dalam folder 'uploads/thumbnail' di dalam proyek kita.
            $fotoFile->move('uploads/thumbnail', $namaFoto); 
        }

        // (3) PERSIAPAN SIMPAN KE DATABASE
        // Ambil ketikan pengguna dari form form input (getPost).
        $nama = $this->request->getPost('nama_wisata'); 
        
        // Buat susunan keranjang data yang pas antara kiri (Nama kolom di tabel database) 
        // dan kanan (Ketikan/Inputan dari formulir HTML pengguna).
        $data = [
            'kategori_id'      => $this->request->getPost('kategori_id'),
            'nama_wisata'      => $nama,
            'slug'             => url_title($nama, '-', true), // Slug adalah versi URL persahabatan (contoh: "Pantai Indah" jadi "pantai-indah")
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'alamat'           => $this->request->getPost('alamat'),
            'jam_operasional'  => $this->request->getPost('jam_operasional'),
            'hari_operasional' => $this->request->getPost('hari_operasional'),
            'harga_tiket'      => $this->request->getPost('harga_tiket') ? $this->request->getPost('harga_tiket') : 0,
            'aturan'           => $this->request->getPost('aturan'),
            'latitude'         => $this->request->getPost('latitude'),
            'longitude'        => $this->request->getPost('longitude'),
            'link_gmaps'       => $this->request->getPost('link_gmaps'),
            'thumbnail'        => $namaFoto, // Simpan hanya nama teks fotonya saja ke database (misal "163abc899.jpg")
        ];

        // Khusus untuk fasilitas yang dicentang lebih dari satu (checkbox/multiple select),
        // kita ubah bentuk susunannya jadi teks koding JSON (json_encode) agar muat dalam 1 kolom database 'fasilitas'.
        $fasilitasIds = $this->request->getPost('fasilitas_ids') ?? [];
        $data['fasilitas'] = json_encode(array_map('intval', $fasilitasIds)); 
        
        // (4) EKSEKUSI SIMPAN
        // Panggil Model Destinasi, jalankan perintah insert (simpan) dengan membawa keranjang $data.
        $destinasiId = $this->destinasiModel->insert($data);
        
        // Catat riwayat aktivitas (Activity Log) bahwa ada pengguna yang baru saja menambah destinasi ini.
        ActivityLogService::log("Menambah destinasi wisata: {$data['nama_wisata']}");

        // Setelah sukses disimpan, tendang (redirect) pengguna ke halaman '/admin/destinasi'
        // dan bawa pesan kilat (flash data) berupa notifikasi sukses.
        return redirect()->to('/admin/destinasi')->with('success', 'Data destinasi berhasil ditambahkan');
    }

    /* ======================================================
       FITUR UBAH DATA (UPDATE) - function update($id)
       Prosesnya mirip dengan Tambah Data, bedanya ini mengubah 
       baris data lama. Parameter $id adalah nomor ID data yang mau diubah.
    ====================================================== */
    public function update($id)
    {
        // (1) CEK VALIDASI
        // Pengecualian pada is_unique: nama wisata tidak boleh sama dengan wisata lain, KECUALI dengan nama wisata dia sendiri (id-nya sendiri).
        $rules = [
            'nama_wisata' => "required|is_unique[destinasi.nama_wisata,id,{$id}]",
            'kategori_id' => 'required',
            'thumbnail'   => 'max_size[thumbnail,2048]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: Nama destinasi mungkin sudah ada atau input tidak lengkap.');
        }

        // Cari dulu baris data lama di tabel 'destinasi' berdasarkan nomor '$id'
        $destinasi = $this->destinasiModel->find($id);
        // Jika datanya ga ketemu (mungkin dihapus orang lain bersamaan), tolak dan berikan pesan error.
        if (!$destinasi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Persiapan data yang mau ditimpa (Kiri: Kolom Database, Kanan: Input Form)
        $nama = $this->request->getPost('nama_wisata');
        $data = [
            'kategori_id'      => $this->request->getPost('kategori_id'),
            'nama_wisata'      => $nama,
            'slug'             => url_title($nama, '-', true),
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'alamat'           => $this->request->getPost('alamat'),
            'jam_operasional'  => $this->request->getPost('jam_operasional'),
            'hari_operasional' => $this->request->getPost('hari_operasional'),
            'harga_tiket'      => $this->request->getPost('harga_tiket') ? $this->request->getPost('harga_tiket') : 0,
            'aturan'           => $this->request->getPost('aturan'),
            'latitude'         => $this->request->getPost('latitude'),
            'longitude'        => $this->request->getPost('longitude'),
            'link_gmaps'       => $this->request->getPost('link_gmaps'),
        ];

        // (2) PROSES GANTI FOTO (Jika Ada)
        $fotoFile = $this->request->getFile('thumbnail');
        // Apakah admin memilih/mengunggah file foto baru di form edit?
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            
            // Pindahkan foto yang baru ke folder uploads/thumbnail
            $namaFoto = $fotoFile->getRandomName();
            $fotoFile->move('uploads/thumbnail', $namaFoto);
            
            // Set agar kolom 'thumbnail' di database berubah namanya jadi foto baru ini
            $data['thumbnail'] = $namaFoto; 
            
            // Hapus file fisik foto lama di folder server supaya tidak memakan kapasitas/storage hardisk.
            // FCPATH adalah jembatan (path) alamat sistem ke direktori root public proyek kita.
            // (Contoh: D:\XAMPP\htdocs\siwisata\public\uploads\thumbnail\namafotolama.jpg)
            if ($destinasi['thumbnail'] && file_exists(FCPATH . 'uploads/thumbnail/' . $destinasi['thumbnail'])) {
                unlink(FCPATH . 'uploads/thumbnail/' . $destinasi['thumbnail']); // 'unlink' artinya hapus file fisik
            }
        }

        // Update susunan data JSON Fasilitas sama seperti saat tambah
        $fasilitasIds = $this->request->getPost('fasilitas_ids') ?? [];
        $data['fasilitas'] = json_encode(array_map('intval', $fasilitasIds));

        // (3) EKSEKUSI UPDATE DATABASE
        // Panggil fungsi 'update' pada model untuk menimpa baris yang memiliki '$id' dengan '$data' baru.
        $this->destinasiModel->update($id, $data);
        
        // Catat aktivitas ke tabel log
        ActivityLogService::log("Mengubah destinasi wisata: {$data['nama_wisata']}");

        return redirect()->to('/admin/destinasi')->with('success', 'Data destinasi berhasil diubah');
    }

    /* ======================================================
       FITUR HAPUS DATA (DELETE) - function destroy($id)
       1. Cari data berdasarkan ID-nya.
       2. Hapus file foto fisiknya dari folder.
       3. Hapus baris datanya dari database.
    ====================================================== */
    public function destroy($id)
    {
        // (1) Temukan data wisata yang mau dihapus di database
        $destinasi = $this->destinasiModel->find($id);

        if ($destinasi) {
            $namaWisata = $destinasi['nama_wisata'];
            
            // (2) HAPUS FILE FISIK FOTO UTAMA
            // Periksa apakah di database dia punya nama foto. Jika punya, cek apakah file aslinya benar-benar ada di folder 'uploads/thumbnail/'.
            if ($destinasi['thumbnail'] && file_exists(FCPATH . 'uploads/thumbnail/' . $destinasi['thumbnail'])) {
                // Perintah 'unlink' digunakan oleh sistem PHP untuk mendelete/menghapus sebuah file.
                unlink(FCPATH . 'uploads/thumbnail/' . $destinasi['thumbnail']);
            }

            // (2b) HAPUS SEMUA FILE GALERI TERKAIT
            // Jika destinasi wisata ini punya kumpulan foto galeri (album), maka foto-foto album tersebut juga wajib dihapus fisiknya.
            $galeriModel = new \App\Models\GaleriModel();
            // Cari seluruh galeri yang berelasi/milik id_destinasi yang bersangkutan
            $galeriList = $galeriModel->where('destinasi_id', $id)->findAll();
            foreach ($galeriList as $media) {
                // Susun rute foldernya (contoh: folder root/uploads/galeri/namafoto.jpg)
                $mediaPath = FCPATH . 'uploads/galeri/' . $media['nama_file'];
                if (file_exists($mediaPath)) {
                    unlink($mediaPath); // Hapus fisiknya satu per satu
                }
            }

            // (3) JALANKAN PERINTAH DELETE PADA DATABASE
            // Hapus baris data di tabel 'destinasi' yang memiliki ID sama dengan parameter $id
            $this->destinasiModel->delete($id);
            
            // Panggil layanan perekam log (ActivityLogService) untuk mencatat bahwa aktivitas menghapus wisata terjadi.
            ActivityLogService::log("Menghapus destinasi wisata: {$namaWisata}");

            // Perintah return mengakhiri proses.
            // redirect()->back() = Kembalikan pengguna ke halaman sebelumnya (tidak jadi pindah halaman).
            // with('success', 'pesan') = Sambil kembali, bawa sebuah data kilat (session flash) bernama 'success' yang berisi pesan notifikasi.
            return redirect()->back()->with('success', 'Data wisata beserta seluruh file galeri berhasil dihapus');
        }

        // Jika kode sampai sini, artinya data tidak ditemukan di atas. Beri pesan error.
        return redirect()->back()->with('error', 'Data tidak ditemukan');
    }
}
