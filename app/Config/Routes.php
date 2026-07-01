<?php

use CodeIgniter\Router\RouteCollection;

/** 
 * @var RouteCollection $routes 
 * Routing dengan penamaan Indonesia untuk halaman pengunjung
 */

// Halaman Utama
$routes->get('/', 'Home::index');

// Halaman Pengunjung
$routes->get('/tentang', 'Home::tentang');
$routes->get('/destinasi', 'Home::destinasi');
$routes->get('/destinasi/(:num)', 'Home::destinasiDetail/$1');  // Detail wisata by ID
$routes->get('/wisata/(:segment)', 'Home::destinasiDetail/$1'); // Detail wisata by Slug
$routes->get('/destinasi/(:segment)', 'Home::destinasi/$1'); // Destinasi wisata by kategori slug
$routes->get('/peta', 'Home::peta');                             // Peta Interaktif — FR-5
$routes->get('/rekomendasi', 'Home::rekomendasi');               // Haversine — FR-3
$routes->get('/galeri', 'Home::galeri');                         // Galeri — FR-4
$routes->get('/layanan', 'Home::layanan');
$routes->get('/kontak', 'Home::kontak');

// Admin Routes
$routes->group('admin', function ($routes) {
    $routes->get('login', 'Auth\Auth::login');
    $routes->post('login/process', 'Auth\Auth::processLogin');
    $routes->get('logout', 'Auth\Auth::logout');
});

// Admin Dashboard & Management (Protected via Filter)
$routes->group('admin', ['filter' => 'adminAuth'], function ($routes) {
    // Redirect /admin ke /admin/dashboard
    $routes->addRedirect('/', 'admin/dashboard');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Master Data (Kategori & Fasilitas)
    $routes->get('master-data', 'Admin\MasterDataController::index');

    $routes->post('kategori/store', 'Admin\KategoriController::store');
    $routes->put('kategori/update/(:num)', 'Admin\KategoriController::update/$1');
    $routes->delete('kategori/delete/(:num)', 'Admin\KategoriController::destroy/$1');

    $routes->post('fasilitas/store', 'Admin\FasilitasController::store');
    $routes->put('fasilitas/update/(:num)', 'Admin\FasilitasController::update/$1');
    $routes->delete('fasilitas/delete/(:num)', 'Admin\FasilitasController::destroy/$1');

    /* ======================================================
       RUTE CRUD DESTINASI (MVC - ROUTES)
       
       Apa itu Routes (Rute)?
       Ibaratkan rute ini sebagai Resepsionis. Ketika pengguna mengetikkan URL 
       atau mengklik tombol yang mengarah ke link tertentu, Routes inilah yang akan 
       menentukan File Controller mana dan Fungsi mana yang harus dipanggil untuk melayaninya.
    ====================================================== */
    
    // 1. TAMPILKAN HALAMAN UTAMA (READ)
    // Jika ada yang mengakses URL 'admin/destinasi' dengan metode GET (hanya membuka halaman biasa),
    // maka panggil fungsi 'index' di dalam file 'DestinasiController'.
    $routes->get('destinasi', 'Admin\DestinasiController::index');
    
    // 2. TERIMA DATA BARU (CREATE)
    // Jika ada form yang mengirim data ke URL 'admin/destinasi/store' dengan metode POST (metode rahasia untuk form tambah),
    // maka panggil fungsi 'store' untuk menyimpan data tersebut ke database.
    $routes->post('destinasi/store', 'Admin\DestinasiController::store');
    
    // 3. TERIMA PERUBAHAN DATA (UPDATE)
    // Jika ada form yang mengirim data ke URL 'admin/destinasi/update/12' dengan metode PUT (metode khusus edit),
    // (:num) artinya dia menangkap angka ID (contoh: 12). Angka itu dikirimkan ke dalam parameter fungsi 'update'.
    $routes->put('destinasi/update/(:num)', 'Admin\DestinasiController::update/$1');
    
    // 4. HAPUS DATA (DELETE)
    // Jika ada perintah hapus yang mengarah ke 'admin/destinasi/delete/12' dengan metode DELETE,
    // maka panggil fungsi 'destroy' dan bawa angka ID-nya untuk dihapus secara permanen.
    $routes->delete('destinasi/delete/(:num)', 'Admin\DestinasiController::destroy/$1');

    // Galeri
    $routes->get('galeri', 'Admin\GaleriController::index');
    $routes->post('galeri/store', 'Admin\GaleriController::store');
    $routes->delete('galeri/delete/(:num)', 'Admin\GaleriController::destroy/$1');

    // WebGIS / Peta Interaktif
    $routes->get('webgis', 'Admin\WebGISController::index');

    // Management Admin
    $routes->get('users', 'Admin\UserController::index');
    $routes->post('users/store', 'Admin\UserController::store');
    $routes->put('users/update/(:num)', 'Admin\UserController::update/$1');
    $routes->delete('users/delete/(:num)', 'Admin\UserController::destroy/$1');

    // Profil Admin
    $routes->get('profil', 'Admin\ProfilController::index');
    $routes->post('profil/update', 'Admin\ProfilController::update');

    // Activity Log
    $routes->get('activity-log', 'Admin\ActivityLogController::index');

    // Custom Error Pages for Admin
    $routes->get('error/404', 'Admin\ErrorController::error404');
    $routes->get('error/500', 'Admin\ErrorController::error500');
});



// Custom 404 Error Page
$routes->set404Override(function () {
    $uri = service('uri');
    if ($uri->getSegment(1) === 'admin' || $uri->getSegment(1) === 'dashboard') {
        return view('admin/error/404');
    }
    return view('pengunjung/error/404');
});
