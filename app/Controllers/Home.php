<?php

namespace App\Controllers;

use App\Models\DestinasiModel;
use App\Models\KategoriWisataModel;
use App\Models\GaleriModel;
use App\Models\KontakPengelolaModel;
use App\Models\FasilitasModel;
use App\Libraries\Haversine;

class Home extends BaseController
{
    protected DestinasiModel $destinasiModel;
    protected KategoriWisataModel $kategoriModel;
    protected GaleriModel $galeriModel;
    protected KontakPengelolaModel $kontakModel;
    protected FasilitasModel $fasilitasModel;

    public function __construct()
    {
        $this->destinasiModel = new DestinasiModel();
        $this->kategoriModel  = new KategoriWisataModel();
        $this->galeriModel    = new GaleriModel();
        $this->kontakModel    = new KontakPengelolaModel();
        $this->fasilitasModel = new FasilitasModel();
    }

    /**
     * Halaman Beranda — tampilkan 6 destinasi terbaru + semua kategori untuk tab filter
     */
    public function index(): string
    {
        $adminModel = new \App\Models\AdminModel();
        
        $db = \Config\Database::connect();
        $builder = $db->table('galeri');
        $builder->select('galeri.*, destinasi.kategori_id, destinasi.nama_wisata');
        $builder->join('destinasi', 'destinasi.id = galeri.destinasi_id');
        $builder->where('galeri.tipe_file', 'foto');
        // Urutkan terbaru
        $builder->orderBy('galeri.id', 'DESC');
        $semuaGaleri = $builder->get()->getResultArray();

        $data = [
            'active'     => 'home',
            'title'      => 'Beranda',
            'kategori'   => $this->kategoriModel->findAll(),
            'destinasi'  => $this->destinasiModel->orderBy('created_at', 'DESC')->findAll(),
            'admins'     => $adminModel->findAll(),
            'semuaGaleri'=> $semuaGaleri,
        ];
        return view('pengunjung/home', $data);
    }

    /**
     * Halaman Tentang Kami
     */
    public function tentang(): string
    {
        $adminModel = new \App\Models\AdminModel();
        $data = [
            'active' => 'tentang', 
            'title' => 'Tentang Kami',
            'admins' => $adminModel->findAll()
        ];
        return view('pengunjung/tentang', $data);
    }

    /**
     * Halaman Daftar Destinasi — filter opsional via query string ?kategori=slug&fasilitas=slug&search=nama
     */
    public function destinasi($kategoriSlug = null): string
    {
        $kategoriSlugFilter = $this->request->getGet('kategori') ?? $kategoriSlug;
        $fasilitasSlug  = $this->request->getGet('fasilitas');
        $searchQuery    = $this->request->getGet('search');

        $builder = $this->destinasiModel;

        if ($kategoriSlugFilter) {
            $kat = $this->kategoriModel->where('slug', $kategoriSlugFilter)->first();
            if ($kat) {
                $builder = $builder->where('kategori_id', $kat['id']);
            }
        }

        $fasIdToFilter = null;
        if ($fasilitasSlug) {
            $fas = $this->fasilitasModel->where('slug', $fasilitasSlug)->first();
            if ($fas) {
                $fasIdToFilter = $fas['id'];
            }
        }

        if ($searchQuery) {
            // like '%s' diujung = like('nama_wisata', 'search', 'after')
            $builder = $builder->like('nama_wisata', $searchQuery, 'after');
        }

        $allDestinasi = $builder->findAll();
        
        if ($fasIdToFilter !== null) {
            $allDestinasi = array_filter($allDestinasi, function($d) use ($fasIdToFilter) {
                $fasIds = json_decode($d['fasilitas'] ?? '[]', true) ?: [];
                return in_array($fasIdToFilter, $fasIds);
            });
        }

        $data = [
            'active'       => 'destinasi',
            'title'        => 'Destinasi Wisata',
            'kategori'     => $this->kategoriModel->findAll(),
            'semua_fas'    => $this->fasilitasModel->findAll(),
            'destinasi'    => $allDestinasi,
            'filter_kat'   => $kategoriSlugFilter,
            'filter_fas'   => $fasilitasSlug,
            'filter_search'=> $searchQuery,
        ];
        return view('pengunjung/destinasi', $data);
    }

    /**
     * Halaman Detail Destinasi — FR-1, FR-4, FR-6
     */
    public function destinasiDetail($idOrSlug): string
    {
        if (is_numeric($idOrSlug)) {
            $wisata = $this->destinasiModel->find($idOrSlug);
        } else {
            $wisata = $this->destinasiModel->where('slug', $idOrSlug)->first();
        }

        if (!$wisata) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Destinasi tidak ditemukan.');
        }

        $id = $wisata['id'];

        $kategori = $this->kategoriModel->find($wisata['kategori_id']);
        $galeri   = $this->galeriModel->where('destinasi_id', $id)->findAll();
        $kontak   = $this->kontakModel->where('destinasi_id', $id)->first();

        // Ambil data fasilitas lengkap berdasarkan array ID JSON
        $fasilitasIDs = json_decode($wisata['fasilitas'] ?? '[]', true);
        $fasilitasList = [];
        if (is_array($fasilitasIDs) && !empty($fasilitasIDs)) {
            $fasilitasList = $this->fasilitasModel->whereIn('id', $fasilitasIDs)->findAll();
        }

        $data = [
            'active'          => 'destinasi',
            'title'           => $wisata['nama_wisata'],
            'wisata'          => $wisata,
            'kategori'        => $kategori,
            'galeri'          => $galeri,
            'kontak'          => $kontak,
            'fasilitas_list'  => $fasilitasList,
        ];
        return view('pengunjung/destinasi_detail', $data);
    }

    /**
     * Halaman Rekomendasi Wisata Terdekat — FR-3 (Haversine)
     * Koordinat user dikirim via AJAX (lat & lon), diproses di sini.
     * GET tanpa param → tampilkan halaman (kosong, geolocation minta izin via JS)
     * GET dengan ?lat=&lon= → hitung + return JSON untuk AJAX
     */
    public function rekomendasi(): string
    {
        $lat = $this->request->getGet('lat');
        $lon = $this->request->getGet('lon');

        $destinasiSorted = [];

        if ($lat !== null && $lon !== null) {
            $semuaDestinasi = $this->destinasiModel
                ->where('latitude IS NOT NULL')
                ->where('longitude IS NOT NULL')
                ->findAll();

            // Hitung jarak Haversine untuk tiap destinasi menggunakan Library Khusus
            foreach ($semuaDestinasi as &$d) {
                $d['jarak_km'] = Haversine::calculateDistance(
                    (float)$lat, (float)$lon,
                    (float)$d['latitude'], (float)$d['longitude']
                );
            }
            unset($d);

            // Urutkan dari jarak terdekat
            usort($semuaDestinasi, fn($a, $b) => $a['jarak_km'] <=> $b['jarak_km']);
            $destinasiSorted = array_slice($semuaDestinasi, 0, 9);
        }

        $kategoriModel = new \App\Models\KategoriWisataModel();
        
        $data = [
            'active'     => 'rekomendasi',
            'title'      => 'Rekomendasi Wisata Terdekat',
            'destinasi'  => $destinasiSorted,
            'kategori'   => $kategoriModel->findAll(),
            'user_lat'   => $lat,
            'user_lon'   => $lon,
        ];
        return view('pengunjung/rekomendasi', $data);
    }

    /**
     * Halaman Peta Interaktif WebGIS — FR-5
     */
    public function peta(): string
    {
        // Ambil semua destinasi yang punya koordinat untuk dijadikan marker
        $destinasi = $this->destinasiModel
            ->where('latitude IS NOT NULL')
            ->where('longitude IS NOT NULL')
            ->findAll();

        $data = [
            'active'    => 'peta',
            'title'     => 'Peta Interaktif Wisata',
            'destinasi' => $destinasi,
        ];
        return view('pengunjung/peta', $data);
    }

    public function galeri(): string
    {
        $kategori = $this->kategoriModel->findAll();

        // Ambil semua galeri beserta info destinasi dan kategorinya
        $allGaleri = $this->galeriModel
            ->select('galeri.*, destinasi.nama_wisata, destinasi.slug as destinasi_slug, destinasi.kategori_id')
            ->join('destinasi', 'destinasi.id = galeri.destinasi_id')
            ->orderBy('galeri.created_at', 'DESC')
            ->findAll();
            
        $groupedByDest = [];
        foreach ($allGaleri as $g) {
            $did = $g['destinasi_id'];
            if (!isset($groupedByDest[$did])) {
                $groupedByDest[$did] = [];
            }
            if (count($groupedByDest[$did]) < 5) { // Maks 5 per destinasi
                $groupedByDest[$did][] = $g;
            }
        }
        
        $galeriAll = [];
        $galeriPerKategori = [];
        foreach ($groupedByDest as $did => $items) {
            foreach ($items as $item) {
                $galeriAll[] = $item;
                $katId = $item['kategori_id'];
                if (!isset($galeriPerKategori[$katId])) {
                    $galeriPerKategori[$katId] = [];
                }
                $galeriPerKategori[$katId][] = $item;
            }
        }

        $data = [
            'active' => 'galeri',
            'title'  => 'Galeri Wisata',
            'kategori' => $kategori,
            'galeriAll' => $galeriAll,
            'galeriPerKategori' => $galeriPerKategori,
        ];
        return view('pengunjung/galeri', $data);
    }

    /**
     * Halaman Layanan
     */
    public function layanan(): string
    {
        $data = ['active' => 'layanan', 'title' => 'Layanan Kami'];
        return view('pengunjung/layanan', $data);
    }

    /**
     * Halaman Tentang / Kontak
     */
    public function kontak(): string
    {
        $data = ['active' => 'kontak', 'title' => 'Hubungi Kami'];
        return view('pengunjung/kontak', $data);
    }

    // -------------------------------------------------------
    // Route lama yang tidak dipakai di MVP — tetap ada agar
    // tidak error 404, redirect ke halaman relevan
    // -------------------------------------------------------
    public function paketWisata(): string
    {
        return redirect()->to(base_url('destinasi'));
    }
    public function pesanTiket(): string
    {
        return redirect()->to(base_url('destinasi'));
    }
    public function tur(): string
    {
        return redirect()->to(base_url('rekomendasi'));
    }
    public function panduan(): string
    {
        return redirect()->to(base_url('/'));
    }
    public function blog(): string
    {
        return redirect()->to(base_url('/'));
    }
    public function testimoni(): string
    {
        return redirect()->to(base_url('/'));
    }

    // (Fungsi Haversine telah dipindahkan ke App/Libraries/Haversine.php)
}
