<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ActivityLogService;

/* ======================================================
   CONTROLLER MANAJEMEN WEBSITE (MVC - CONTROLLER)

   Daftar Fitur (Fungsi/Method) di dalam file ini:
   - function index()       = Menampilkan halaman Manajemen Website
                              beserta preview background hero.
   - function simpanHero()  = Menyimpan SEMUA gambar background hero
                              (3 slide beranda + 1 halaman lain)
                              sekaligus dalam satu tombol simpan.
====================================================== */

class WebsiteController extends BaseController
{
    /* ======================================================
       PETA SLOT HERO

       Kunci input form => nama kunci pengaturan di database.
       Dipakai bersama oleh index() dan simpanHero().
    ====================================================== */
    private array $petaSlot = [
        'index1' => 'hero_index_1',
        'index2' => 'hero_index_2',
        'index3' => 'hero_index_3',
        'umum'   => 'hero_umum',
    ];

    /* ======================================================
       FITUR TAMPILKAN HALAMAN (READ) - function index()
    ====================================================== */
    public function index()
    {
        $data = [
            'title' => 'Manajemen Website',
            'hero'  => [
                'index1' => hero_url('hero_index_1', 'assets/img/carousel-2.jpg'),
                'index2' => hero_url('hero_index_2', 'assets/img/carousel-1.jpg'),
                'index3' => hero_url('hero_index_3', 'assets/img/carousel-3.jpg'),
                'umum'   => hero_url('hero_umum',   'assets/img/breadcrumb-bg.jpg'),
            ],
        ];

        return view('admin/website/index', $data);
    }

    /* ======================================================
       FITUR SIMPAN SEMUA BACKGROUND - function simpanHero()

       1. Periksa keempat slot gambar dari satu form yang sama.
       2. Validasi tiap file yang diunggah (gambar, maks 5MB).
       3. Pindahkan file valid ke folder uploads/hero/.
       4. Hapus gambar lama dari server jika diganti.
       5. Simpan/perbarui baris pengaturan di database.
       6. Catat aktivitas lalu kembali dengan pesan hasil.
    ====================================================== */
    public function simpanHero()
    {
        $builder     = db_connect()->table('pengaturan');
        $jumlahBaru  = 0;
        $namaGagal   = [];

        // Nama tampilan tiap slot untuk pesan error yang detail.
        $label = [
            'index1' => 'Slide 1 Beranda',
            'index2' => 'Slide 2 Beranda',
            'index3' => 'Slide 3 Beranda',
            'umum'   => 'Background Halaman Lain',
        ];

        foreach ($this->petaSlot as $input => $kunci) {
            $gambar = $this->request->getFile($input);

            // Lewati slot yang tidak mengunggah apa pun (opsional).
            if (!$gambar || !$gambar->isValid()) {
                continue;
            }

            // Validasi per-file: harus gambar & maksimal 5MB.
            if (!$this->validate([$input => "is_image[{$input}]|max_size[{$input},5120]"]) || $gambar->hasMoved()) {
                $namaGagal[] = $label[$input] . ' (bukan gambar / lebih dari 5MB)';
                continue;
            }

            // Pindahkan gambar baru ke server dengan nama acak.
            $namaFile = $gambar->getRandomName();
            $gambar->move('uploads/hero', $namaFile);

            // Hapus gambar lama dari server jika ada.
            $lama = pengaturan_ambil($kunci);
            if ($lama && is_file('uploads/hero/' . $lama)) {
                unlink('uploads/hero/' . $lama);
            }

            // Simpan atau perbarui baris pengaturan di database.
            $sudahAda = $builder->where('kunci', $kunci)->countAllResults() > 0;

            if ($sudahAda) {
                $builder->where('kunci', $kunci)->update([
                    'nilai'      => $namaFile,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                $builder->insert([
                    'kunci'      => $kunci,
                    'nilai'      => $namaFile,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $jumlahBaru++;
        }

        // Tidak ada file yang dikirim sama sekali.
        if ($jumlahBaru === 0 && empty($namaGagal)) {
            return redirect()->back()->with(
                'error',
                'Tidak ada gambar yang disimpan. Silakan pilih minimal satu file gambar terlebih dahulu.'
            );
        }

        ActivityLogService::log("Menyimpan {$jumlahBaru} background hero melalui Manajemen Website");

        // Ada sebagian gagal -> pesan detail slot mana yang bermasalah.
        if (!empty($namaGagal)) {
            return redirect()->back()->with(
                'error',
                "{$jumlahBaru} gambar tersimpan, namun " . count($namaGagal) . ' gagal: ' . implode('; ', $namaGagal) . '.'
            );
        }

        return redirect()->back()->with(
            'success',
            "Berhasil! {$jumlahBaru} background website tersimpan dan langsung aktif di halaman pengunjung."
        );
    }
}
