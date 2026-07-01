<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DestinasiModel;
use App\Models\KategoriWisataModel;

/* ======================================================
   CONTROLLER WEB-GIS (MVC - CONTROLLER)
   
   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan
   database pengolahan data (Model). Jika ada pengguna yang mengklik
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function index() = Mengambil data wisata dan mengubahnya menjadi titik koordinat (marker) pada peta LeafletJS.
====================================================== */

class WebGISController extends BaseController
{
    protected $destinasiModel;
    protected $kategoriModel;

    /* ======================================================
       PERSIAPAN CONTROLLER - function __construct()

       Fungsi ini akan dijalankan otomatis saat Controller dipanggil.
       Tujuannya untuk menyiapkan Model agar dapat digunakan oleh
       seluruh fungsi di dalam Controller ini.
    ====================================================== */
    public function __construct()
    {
        // Panggil Model Destinasi dan Kategori agar Controller dapat
        // berkomunikasi dengan tabel database yang dibutuhkan.
        $this->destinasiModel = new DestinasiModel();
        $this->kategoriModel  = new KategoriWisataModel();
    }

    /* ======================================================
       FITUR BACA & FILTER PETA (READ) - function index()

       1. Siapkan data fasilitas dan kategori untuk fitur filter di peta.
       2. Tangkap nilai filter dari URL (jika ada).
       3. Ambil data destinasi berdasarkan syarat filter.
       4. Olah data lokasi (latitude/longitude) menjadi format PopUp.
       5. Kirim data ke View peta Leaflet.
    ====================================================== */
    public function index()
    {
        // (1) Siapkan Filter Kategori dan Fasilitas.
        $kategoriList   = $this->kategoriModel->findAll();
        $fasilitasModel = new \App\Models\FasilitasModel();
        $fasilitasList  = $fasilitasModel->findAll();

        // (2) Tangkap parameter URL jika pengguna menekan tombol filter.
        // Contoh URL: /admin/webgis?kategori=pantai
        $kategori_slug  = $this->request->getGet('kategori');
        $fasilitas_slug = $this->request->getGet('fasilitas');

        // (3) Mulai menyusun perintah pengambilan data destinasi.
        // Kita gabungkan (join) dengan tabel kategori wisata.
        $this->destinasiModel->select('destinasi.*, kategori_wisata.nama_kategori')
                             ->join('kategori_wisata', 'kategori_wisata.id = destinasi.kategori_id');
        
        // Terapkan Filter Kategori (Jika Ada)
        if (!empty($kategori_slug)) {
            
            $kat = $this->kategoriModel->where('slug', $kategori_slug)->first();
            
            if ($kat) {
                $this->destinasiModel->where('destinasi.kategori_id', $kat['id']);
            }
        }

        // Terapkan Filter Fasilitas (Tahap 1: Temukan ID Fasilitasnya)
        $fasilitasFilterId = null;

        if (!empty($fasilitas_slug)) {
            
            $fas = $fasilitasModel->where('slug', $fasilitas_slug)->first();
            
            if ($fas) {
                $fasilitasFilterId = $fas['id'];
            }
        }

        // Ambil data dari database.
        $destinasiList = $this->destinasiModel->findAll();

        // Terapkan Filter Fasilitas (Tahap 2: Saring datanya menggunakan PHP).
        // Karena fasilitas tersimpan dalam format Array JSON, filternya kita lakukan manual di sini.
        if ($fasilitasFilterId !== null) {
            
            $destinasiList = array_filter($destinasiList, function($d) use ($fasilitasFilterId) {
                
                // Ubah teks JSON menjadi Array.
                $fasIds = json_decode($d['fasilitas'] ?? '[]', true) ?: [];
                
                // Pastikan fasilitas yang dicari ada di dalam keranjang array tersebut.
                return in_array($fasilitasFilterId, $fasIds);
            });
        }

        // (4) MEMBUAT TITIK PETA (MARKER & POPUP HTML)
        // Kita hanya mengambil destinasi yang memiliki informasi latitude dan longitude.
        $points = [];

        foreach ($destinasiList as $destinasi) {
            
            if (!empty($destinasi['latitude']) && !empty($destinasi['longitude'])) {
                
                // Siapkan gambar thumbnail. Jika tidak ada, beri gambar kosong/placeholder.
                $fotoUrl = $destinasi['thumbnail'] 
                    ? base_url('uploads/thumbnail/' . $destinasi['thumbnail']) 
                    : 'https://via.placeholder.com/300x150?text=No+Image';

                // Buat tautan menuju aplikasi Google Maps asli.
                $gmapsLink = "https://www.google.com/maps/place/" . urlencode($destinasi['nama_wisata']) . "/@{$destinasi['latitude']},{$destinasi['longitude']},17z";

                // Rakitan Desain HTML untuk Kartu Informasi (Popup) saat titik di peta diklik.
                $popupHtml = '
                    <div class="card border-0 m-0" style="width: 280px;">
                        <img src="' . $fotoUrl . '" class="card-img-top object-fit-cover" alt="Foto" style="height: 120px;">
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold text-primary mb-1 text-truncate" title="' . esc($destinasi['nama_wisata']) . '">' . esc($destinasi['nama_wisata']) . '</h6>
                            <p class="card-text small text-secondary mb-2"><i class="bi bi-tag-fill me-1 text-warning"></i> ' . esc($destinasi['nama_kategori']) . '</p>
                            
                            <table class="table table-sm table-borderless small mb-2">
                                <tr>
                                    <td class="p-0 text-muted" width="40%">Jam Buka</td>
                                    <td class="p-0 fw-semibold">: ' . esc($destinasi['jam_operasional'] ?? '-') . '</td>
                                </tr>
                                <tr>
                                    <td class="p-0 text-muted" width="30%">Alamat</td>
                                    <td class="p-0 fw-semibold" id="alamat-' . $destinasi['id'] . '">
                                        <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span> <span class="fs-7 text-muted">Memuat...</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0 text-muted">Koordinat</td>
                                    <td class="p-0 fw-semibold fs-7">' . esc($destinasi['latitude']) . ', ' . esc($destinasi['longitude']) . '</td>
                                </tr>
                            </table>
                            <div class="d-grid gap-2 mt-3">
                                <a href="' . $gmapsLink . '" target="_blank" class="btn btn-primary btn-sm rounded-pill text-white"><i class="bi bi-geo-alt-fill me-1"></i> Buka di Google Maps</a>
                            </div>
                        </div>
                    </div>
                ';

                // Simpan rakitan titik peta ke array $points.
                $points[] = [
                    'id'        => $destinasi['id'],                        // ID wisata
                    'name'      => $destinasi['nama_wisata'],               // Nama wisata
                    'category'  => $destinasi['nama_kategori'],             // Kategori wisata
                    'latitude'  => $destinasi['latitude'],                  // Kordinat Garis Lintang
                    'longitude' => $destinasi['longitude'],                 // Kordinat Garis Bujur
                    'popupHtml' => $popupHtml                               // Desain kartu popup
                ];
            }
        }

        // (5) Bungkus seluruh data filter dan data peta ke dalam
        // satu keranjang/variabel bernama $data.
        $data = [
            'title'          => 'Peta Interaktif (WebGIS)',                 // Judul halaman
            'kategoriList'   => $kategoriList,                              // Kumpulan kategori (Untuk Select Option)
            'fasilitasList'  => $fasilitasList,                             // Kumpulan fasilitas (Untuk Select Option)
            'points'         => $points,                                    // Kumpulan koordinat marker LeafletJS
            'kategori_slug'  => $kategori_slug,                             // Filter kategori aktif saat ini
            'fasilitas_slug' => $fasilitas_slug                             // Filter fasilitas aktif saat ini
        ];
        
        // Kembalikan (return) hasil proses ini dengan memuat (view)
        // file halaman 'admin/webgis/index.php'.
        return view('admin/webgis/index', $data);
    }
}
