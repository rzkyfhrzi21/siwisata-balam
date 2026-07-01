<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/* ======================================================
   CONTROLLER DASHBOARD (MVC - CONTROLLER)
   
   Apa itu Controller?
   Controller adalah jembatan antara tampilan layar (View) dan 
   database pengolahan data (Model). Jika ada pengguna yang mengklik 
   tombol di layar, Controller-lah yang memproses permintaan tersebut.

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function index() = Mengambil data dari berbagai tabel, mengolahnya menjadi statistik dan grafik, lalu menampilkannya pada halaman Dashboard Admin.
====================================================== */

class Dashboard extends BaseController
{
    /* ======================================================
       FITUR BACA & TAMPIL DASHBOARD - function index()

       1. Ambil data dari seluruh tabel yang dibutuhkan.
       2. Olah data menjadi statistik dan grafik.
       3. Bungkus seluruh hasil ke dalam variabel $data.
       4. Kirim dan tampilkan data tersebut ke halaman HTML/View.
    ====================================================== */
    public function index()
    {
        // (1) Panggil seluruh Model agar Controller dapat berkomunikasi
        // dengan masing-masing tabel di dalam database.
        $logModel       = new \App\Models\ActivityLogModel();
        $destinasiModel = new \App\Models\DestinasiModel();
        $kategoriModel  = new \App\Models\KategoriWisataModel();
        $adminModel     = new \App\Models\AdminModel();
        $galeriModel    = new \App\Models\GaleriModel();
        $fasilitasModel = new \App\Models\FasilitasModel();

        // Ambil seluruh data destinasi dari database.
        // Data inilah yang nantinya akan diolah menjadi berbagai statistik pada Dashboard.
        $allDestinasi = $destinasiModel->findAll();

        // (2) OLAH DATA GRAFIK KATEGORI
        // Hitung jumlah destinasi pada masing-masing kategori
        // agar dapat ditampilkan dalam bentuk grafik.
        $kategoriStats = [];
        $kategoriList  = $kategoriModel->findAll();

        // Periksa setiap kategori satu per satu,
        // kemudian hitung berapa destinasi yang termasuk ke dalam kategori tersebut.
        foreach ($kategoriList as $k) {
            $count = 0;

            foreach ($allDestinasi as $d) {
                if ($d['kategori_id'] == $k['id']) $count++; // Jika kategori sama, tambahkan jumlahnya
            }

            if ($count > 0) {
                $kategoriStats[] = [
                    'label' => $k['nama_kategori'],
                    'count' => $count
                ];
            }
        }

        // (3) OLAH DATA HARGA TIKET DAN KELENGKAPAN GPS
        // Data pada bagian ini digunakan untuk membuat grafik harga tiket,
        // menghitung rata-rata harga tiket, serta mengecek kelengkapan koordinat peta.
        $totalTicketPrice   = 0;
        $destWithPriceCount = 0;

        // Siapkan kelompok harga tiket yang akan ditampilkan pada grafik.
        $priceRanges = [
            'Gratis'         => 0,
            '< Rp 20rb'      => 0,
            'Rp 20rb - 50rb' => 0,
            '> Rp 50rb'      => 0
        ];

        $validGeoCount   = 0; // Menghitung jumlah destinasi yang sudah memiliki titik koordinat GPS
        $fasilitasCounts = []; // Menyimpan jumlah penggunaan setiap fasilitas

        // Periksa seluruh data destinasi satu per satu.
        foreach ($allDestinasi as $d) {

            // Kelompokkan destinasi berdasarkan harga tiketnya.
            $price = floatval($d['harga_tiket']);

            if ($price == 0) {
                $priceRanges['Gratis']++;
            } elseif ($price < 20000) {
                $priceRanges['< Rp 20rb']++;
                $totalTicketPrice += $price;
                $destWithPriceCount++;
            } elseif ($price <= 50000) {
                $priceRanges['Rp 20rb - 50rb']++;
                $totalTicketPrice += $price;
                $destWithPriceCount++;
            } else {
                $priceRanges['> Rp 50rb']++;
                $totalTicketPrice += $price;
                $destWithPriceCount++;
            }

            // Periksa apakah destinasi sudah memiliki Latitude dan Longitude.
            if (!empty($d['latitude']) && !empty($d['longitude'])) {
                $validGeoCount++;
            }

            // Hitung berapa kali setiap fasilitas digunakan oleh seluruh destinasi.
            // Data fasilitas disimpan dalam format JSON sehingga perlu diubah kembali menjadi Array.
            $fasIds = json_decode($d['fasilitas'] ?? '[]', true) ?: [];

            foreach ($fasIds as $fid) {
                if (!isset($fasilitasCounts[$fid])) $fasilitasCounts[$fid] = 0;

                $fasilitasCounts[$fid]++; // Tambahkan jumlah penggunaan fasilitas
            }
        }

        // Hitung rata-rata harga tiket dari seluruh destinasi yang berbayar.
        $avgTicketPrice = $destWithPriceCount > 0 ? ($totalTicketPrice / $destWithPriceCount) : 0;

        // Hitung persentase kelengkapan koordinat GPS seluruh destinasi.
        $geoCompleteness = count($allDestinasi) > 0 ? round(($validGeoCount / count($allDestinasi)) * 100) : 0;

        // (4) OLAH DATA FASILITAS TERPOPULER
        // Cari fasilitas yang paling sering digunakan oleh seluruh destinasi wisata.
        arsort($fasilitasCounts); // Urutkan mulai dari yang paling banyak digunakan

        // Ambil hanya 7 fasilitas dengan jumlah penggunaan terbanyak.
        $topFasIds   = array_slice(array_keys($fasilitasCounts), 0, 7);
        $topFasStats = [];

        if (!empty($topFasIds)) {

            // Ambil nama fasilitas berdasarkan ID yang telah diperoleh sebelumnya.
            $fasilitasList = $fasilitasModel->whereIn('id', $topFasIds)->findAll();

            // Buat pasangan ID => Nama Fasilitas agar mudah dicari.
            $fasMap = [];

            foreach ($fasilitasList as $f) {
                $fasMap[$f['id']] = $f['nama_fasilitas'];
            }

            // Susun data yang nantinya digunakan oleh grafik fasilitas.
            foreach ($topFasIds as $fid) {
                if (isset($fasMap[$fid])) {
                    $topFasStats[] = [
                        'label' => $fasMap[$fid],
                        'count' => $fasilitasCounts[$fid]
                    ];
                }
            }
        }

        // (5) Bungkus seluruh hasil pengolahan tadi ke dalam
        // satu keranjang/variabel bernama $data.
        // Variabel inilah yang nantinya dikirim ke halaman Dashboard.
        $data = [
            'title'            => 'Dashboard Admin',
            'page'             => 'dashboard',

            // Data aktivitas admin
            'logs'             => $logModel->orderBy('created_at', 'DESC')->findAll(7), // Ambil 7 aktivitas terbaru
            'total_logs'       => $logModel->countAllResults(), // Hitung seluruh jumlah aktivitas

            // Ringkasan angka utama Dashboard
            'total_destinasi'  => count($allDestinasi),
            'avg_ticket_price' => $avgTicketPrice,
            'total_galeri'     => $galeriModel->countAllResults(),
            'geo_completeness' => $geoCompleteness,

            // Data Grafik Kategori
            'chart_labels'     => array_column($kategoriStats, 'label'),
            'chart_series'     => array_map('intval', array_column($kategoriStats, 'count')),

            // Data Grafik Harga Tiket
            'price_labels'     => array_keys($priceRanges),
            'price_series'     => array_values($priceRanges),

            // Data Grafik Fasilitas
            'top_fas_labels'   => array_column($topFasStats, 'label'),
            'top_fas_series'   => array_map('intval', array_column($topFasStats, 'count')),
        ];

        // (6) Kembalikan (return) hasil proses ini dengan memuat (view)
        // file halaman 'admin/dashboard.php'.
        // Jangan lupa bawa keranjang $data-nya ke halaman tersebut.
        return view('admin/dashboard', $data);
    }
}
