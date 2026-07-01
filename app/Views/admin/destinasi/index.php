<?php
/* ======================================================
   VIEW DESTINASI (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna. Di sini, Controller mengirimkan kotak berisi 
   data wisata dari database (variabel $destinasi), lalu View ini membongkar 
   kotak tersebut dan menyusunnya menjadi bentuk Tabel yang rapi.
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('title') ?>
Manajemen Destinasi Wisata
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Destinasi</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- ======================================================
     KOTAK RINGKASAN (SMALL BOXES)
     
     Bagian ini menampilkan kotak-kotak ringkasan di bagian atas halaman.
     Fungsinya adalah untuk memberikan informasi statistik cepat kepada admin,
     seperti jumlah "Total Destinasi Wisata" dan nama destinasi "Terbaru".
====================================================== -->
<div class="row mb-4">
    <div class="col-lg-6 col-md-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3><?= count($destinasi) ?></h3>
                <p>Total Destinasi Wisata</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>
            </svg>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3>Terbaru</h3>
                <p><?= !empty($destinasi) ? esc($destinasi[0]['nama_wisata']) : '-' ?></p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- ======================================================
     BARIS GRAFIK (ANALYTICS CHARTS)
     
     Bagian ini membangun kerangka layout untuk menampilkan grafik (charts).
     Berisi dua kotak (Card):
     1. Grafik Sebaran Harga Tiket (Kiri)
     2. Grafik Komposisi Kategori Wisata (Kanan)
     Bentuk grafiknya sendiri nanti digambar oleh JavaScript (ApexCharts)
     di bagian paling bawah file ini.
====================================================== -->
<div class="row mb-4">
    <div class="col-lg-7">
        <div class="card shadow h-100">
            <div class="card-header border-0">
                <h3 class="card-title">Sebaran Harga Tiket Destinasi</h3>
            </div>
            <div class="card-body">
                <div id="chart-harga-tiket"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card shadow h-100">
            <div class="card-header border-0">
                <h3 class="card-title">Komposisi Kategori Wisata</h3>
            </div>
            <div class="card-body">
                <div id="chart-kategori-destinasi"></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title m-0">Daftar Destinasi Wisata</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg"></i> Tambah Destinasi
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3 align-items-center">
            <div class="d-flex gap-2">
                <button id="export-csv" class="btn btn-outline-secondary btn-sm"><i class="bi bi-filetype-csv me-1"></i> Export CSV</button>
                <button id="export-json" class="btn btn-outline-secondary btn-sm"><i class="bi bi-filetype-json me-1"></i> Export JSON</button>
                <button id="print-table" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer me-1"></i> Print</button>
            </div>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="table-filter" class="form-control form-control-sm" placeholder="Cari destinasi...">
            </div>
        </div>

        <div id="destinasi-table"></div>
    </div>
</div>

<!-- ======================================================
     MODAL TAMBAH (CREATE)
     
     Ini adalah formulir (form) popup yang muncul saat tombol "Tambah" diklik.
     Form ini memiliki atribut action=".../admin/destinasi/store".
     Artinya, saat admin mengklik tombol "Simpan", semua kotak isian (input) di bawah ini
     akan dibungkus dan dikirimkan ke dalam Controller fungsi 'store()'.
     
     Kolom Isian (Input) yang ada di form ini:
     - Nama Wisata        (Wajib)
     - Kategori           (Wajib, Dropdown dari tabel Kategori)
     - Deskripsi          (Tidak Wajib)
     - Alamat             (Wajib)
     - Jam Operasional    (Tidak Wajib)
     - Hari Operasional   (Tidak Wajib)
     - Harga Tiket        (Tidak Wajib)
     - Titik Peta Lokasi  (Latitude & Longitude otomatis/manual)
     - Link Google Maps   (Tidak Wajib)
     - Thumbnail          (Upload Foto)
     - Fasilitas          (Pilih lebih dari satu)
     - Aturan             (Tidak Wajib)
====================================================== -->
<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="<?= base_url('admin/destinasi/store') ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="modal-header text-bg-primary">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Destinasi Wisata</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_wisata" class="form-label">Nama Wisata <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_wisata" name="nama_wisata" required>
                                <div class="invalid-feedback">Nama wisata wajib diisi.</div>
                            </div>
                            <div class="mb-3">
                                <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" id="kategori_id" name="kategori_id" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($kategori as $kat): ?>
                                        <option value="<?= $kat['id'] ?>"><?= esc($kat['nama_kategori']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Kategori wajib dipilih.</div>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="8"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="4" required></textarea>
                                <div class="invalid-feedback">Alamat wajib diisi.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jam_operasional" class="form-label">Jam Operasional</label>
                                        <input type="text" class="form-control" id="jam_operasional" name="jam_operasional" placeholder="08:00 - 17:00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="hari_operasional" class="form-label">Hari Operasional</label>
                                        <input type="text" class="form-control" id="hari_operasional" name="hari_operasional" placeholder="Senin - Minggu">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="harga_tiket" class="form-label">Harga Tiket (Rp)</label>
                                <input type="number" class="form-control" id="harga_tiket" name="harga_tiket" value="0">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">Titik Peta Lokasi</label>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-0" id="btn-locate-tambah">
                                            <i class="bi bi-crosshair"></i> Ambil Lokasi Saya
                                        </button>
                                    </div>
                                    <div id="mapPickerTambah" class="rounded border mb-3" style="height: 200px; width: 100%; z-index: 1;"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="-5.42544">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="105.25804">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="link_gmaps" class="form-label">Link Google Maps</label>
                                <input type="url" class="form-control" id="link_gmaps" name="link_gmaps" placeholder="https://maps.app.goo.gl/...">
                            </div>
                            <div class="mb-3">
                                <label for="thumbnail" class="form-label">Thumbnail</label>
                                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                            </div>
                        </div>
                        <div class="col-12">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Fasilitas</label>
                                        <div class="select2-purple-wrap">
                                            <select class="form-select select2-purple" name="fasilitas_ids[]" id="fasilitas_ids" multiple="multiple" data-placeholder="Pilih Fasilitas..." style="width: 100%;">
                                                <?php foreach ($fasilitas_list as $f): ?>
                                                    <option value="<?= $f['id'] ?>" data-icon="<?= esc($f['icon'] ?? '') ?>"><?= esc($f['nama_fasilitas']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="aturan" class="form-label">Aturan</label>
                                        <textarea class="form-control" id="aturan" name="aturan" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================
     MODAL UBAH (UPDATE)
     
     Ini adalah formulir popup untuk mengedit data.
     Awalnya form ini kosong. Namun berkat bantuan JavaScript (di bagian bawah file ini),
     saat admin mengklik tombol "Edit" kuning di tabel, JavaScript akan menarik 
     data baris tersebut dan mengisikannya otomatis ke dalam form edit ini.
     
     Saat tombol "Update" diklik, data baru akan dikirimkan ke 
     Controller fungsi 'update()' beserta ID wisata tersebut.
     
     Kolom Isian (Input) sama persis dengan form Tambah, namun kolom Thumbnail
     boleh dikosongkan jika admin tidak ingin mengganti foto yang lama.
====================================================== -->
<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="formEdit" action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header text-bg-warning">
                    <h5 class="modal-title" id="modalEditLabel">Edit Destinasi Wisata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nama_wisata" class="form-label">Nama Wisata <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nama_wisata" name="nama_wisata" required>
                                <div class="invalid-feedback">Nama wisata wajib diisi.</div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_kategori_id" name="kategori_id" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($kategori as $kat): ?>
                                        <option value="<?= $kat['id'] ?>"><?= esc($kat['nama_kategori']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Kategori wajib dipilih.</div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="8"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit_alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="edit_alamat" name="alamat" rows="4" required></textarea>
                                <div class="invalid-feedback">Alamat wajib diisi.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_jam_operasional" class="form-label">Jam Operasional</label>
                                        <input type="text" class="form-control" id="edit_jam_operasional" name="jam_operasional">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_hari_operasional" class="form-label">Hari Operasional</label>
                                        <input type="text" class="form-control" id="edit_hari_operasional" name="hari_operasional">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_harga_tiket" class="form-label">Harga Tiket (Rp)</label>
                                <input type="number" class="form-control" id="edit_harga_tiket" name="harga_tiket">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">Titik Peta Lokasi</label>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-0" id="btn-locate-edit">
                                            <i class="bi bi-crosshair"></i> Ambil Lokasi Saya
                                        </button>
                                    </div>
                                    <div id="mapPickerEdit" class="rounded border mb-3" style="height: 200px; width: 100%; z-index: 1;"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_latitude" class="form-label">Latitude</label>
                                        <input type="text" class="form-control" id="edit_latitude" name="latitude">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_longitude" class="form-label">Longitude</label>
                                        <input type="text" class="form-control" id="edit_longitude" name="longitude">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_link_gmaps" class="form-label">Link Google Maps</label>
                                <input type="url" class="form-control" id="edit_link_gmaps" name="link_gmaps" placeholder="https://maps.app.goo.gl/...">
                            </div>
                            <div class="mb-3">
                                <label for="edit_thumbnail" class="form-label">Thumbnail (Biarkan kosong jika tidak diubah)</label>
                                <input type="file" class="form-control" id="edit_thumbnail" name="thumbnail" accept="image/*">
                            </div>
                        </div>
                        <div class="col-12">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Fasilitas</label>
                                        <div class="select2-purple-wrap">
                                            <select class="form-select select2-purple" name="fasilitas_ids[]" id="edit_fasilitas_ids" multiple="multiple" data-placeholder="Pilih Fasilitas..." style="width: 100%;">
                                                <?php foreach ($fasilitas_list as $f): ?>
                                                    <option value="<?= $f['id'] ?>" data-icon="<?= esc($f['icon'] ?? '') ?>"><?= esc($f['nama_fasilitas']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_aturan" class="form-label">Aturan</label>
                                        <textarea class="form-control" id="edit_aturan" name="aturan" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================
     MODAL PREVIEW FOTO (READ)
     
     Ini adalah kotak popup sederhana tanpa isian/form.
     Saat admin mengklik foto kecil (thumbnail) yang ada di tabel, 
     modal ini akan otomatis muncul menampilkan foto tersebut dalam ukuran besar.
====================================================== -->
<!-- Modal Preview Foto -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0 justify-content-end" style="position: absolute; right: 0; top: -40px; z-index: 1055;">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0 position-relative">
                <img id="previewImage" src="" class="img-fluid rounded shadow-lg skeleton-effect" style="max-height: 85vh; object-fit: contain; width: 100%; background: rgba(0,0,0,0.5);" alt="Preview" onload="this.classList.remove('skeleton-effect')">
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- Select2 Dependencies -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    .select2-purple-wrap .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background-color: #0d6efd !important;
        border-color: #0a58ca !important;
        color: #fff !important;
    }

    .select2-purple-wrap .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
        color: #fff !important;
        filter: invert(1) brightness(200%);
        opacity: 0.8;
    }

    .select2-purple-wrap .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove:hover {
        opacity: 1;
    }

    /* Memunculkan panah dropdown (tanda tambah/pilih) di Select2 Multiple */
    .select2-container--bootstrap-5 .select2-selection--multiple {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right 0.75rem center !important;
        background-size: 16px 12px !important;
        padding-right: 2.25rem !important;
        cursor: pointer;
    }

    /* Skeleton Loading Effect */
    .skeleton-effect {
        background-color: #e2e5e7;
        background-image: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0));
        background-size: 200px 100%;
        background-repeat: no-repeat;
        animation: shimmer 1.5s infinite linear;
        color: transparent;
    }

    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }

        100% {
            background-position: calc(200px + 100%) 0;
        }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validation
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            });

        const tableData = <?= json_encode($destinasi) ?>;

        const actionFormatter = (cell) => {
            const row = cell.getRow().getData();
            // Base64 encode the JSON to avoid quoting issues in HTML attributes
            const rowJson = btoa(unescape(encodeURIComponent(JSON.stringify(row))));
            const deleteUrl = `<?= base_url('admin/destinasi/delete') ?>/${row.id}`;

            return `
            <button class="btn btn-warning btn-sm" onclick="editData('${rowJson}')"><i class="bi bi-pencil"></i></button>
            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('${deleteUrl}')"><i class="bi bi-trash"></i></button>
        `;
        };

        const imageFormatter = (cell) => {
            const val = cell.getValue();
            if (val) {
                const imgUrl = val.startsWith('uploads/') ? `<?= base_url('') ?>${val}` : `<?= base_url('uploads/thumbnail/') ?>${val}`;
                // Ukuran 60x60px adalah ukuran ideal untuk avatar/thumbnail di datatable agar rapi
                return `<img src="${imgUrl}" alt="Foto" class="img-thumbnail p-1 skeleton-effect" style="width: 60px; height: 60px; object-fit: cover; cursor: pointer; transition: transform 0.2s;" loading="lazy" onload="this.classList.remove('skeleton-effect')" onclick="showPreview('${imgUrl}')" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">`;
            }
            return `<span class="badge bg-secondary">No Image</span>`;
        };

        window.showPreview = function(url) {
            document.getElementById('previewImage').src = url;
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        };

        const table = new Tabulator("#destinasi-table", {
            data: tableData,
            layout: "fitColumns",
            pagination: "local",
            paginationSize: 10,
            paginationSizeSelector: [10, 25, 50, 100],
            columns: [{
                    title: "No",
                    field: "id",
                    width: 60,
                    headerSort: false,
                    formatter: "rownum"
                },
                {
                    title: "Foto",
                    field: "thumbnail",
                    formatter: imageFormatter,
                    width: 100,
                    hozAlign: "center",
                    headerSort: false
                },
                {
                    title: "Nama Wisata",
                    field: "nama_wisata",
                    headerFilter: "input"
                },
                {
                    title: "Slug",
                    field: "slug",
                    headerFilter: "input"
                },
                {
                    title: "Kategori",
                    field: "nama_kategori",
                    headerFilter: "input"
                },
                {
                    title: "Harga Tiket",
                    field: "harga_tiket",
                    formatter: "money",
                    formatterParams: {
                        symbol: "Rp ",
                        precision: 0,
                        thousand: "."
                    }
                },
                {
                    title: "Aksi",
                    headerSort: false,
                    formatter: actionFormatter,
                    width: 120,
                    hozAlign: "center"
                }
            ],
        });

        document.getElementById('table-filter').addEventListener('input', (e) => {
            const value = e.target.value;
            if (value) {
                table.setFilter([
                    [{
                            field: 'nama_wisata',
                            type: 'like',
                            value: value
                        },
                        {
                            field: 'nama_kategori',
                            type: 'like',
                            value: value
                        }
                    ]
                ]);
            } else {
                table.clearFilter();
            }
        });

        document.getElementById('export-csv').addEventListener('click', () => table.download('csv', 'destinasi.csv'));
        document.getElementById('export-json').addEventListener('click', () => table.download('json', 'destinasi.json'));
        document.getElementById('print-table').addEventListener('click', () => table.print(false, true));

        window.editData = function(rowJsonStr) {
            const row = JSON.parse(decodeURIComponent(escape(atob(rowJsonStr))));
            document.getElementById('formEdit').action = `<?= base_url('admin/destinasi/update') ?>/${row.id}`;

            document.getElementById('edit_kategori_id').value = row.kategori_id;
            document.getElementById('edit_nama_wisata').value = row.nama_wisata || '';
            document.getElementById('edit_deskripsi').value = row.deskripsi || '';
            document.getElementById('edit_alamat').value = row.alamat || '';
            document.getElementById('edit_link_gmaps').value = row.link_gmaps || '';
            document.getElementById('edit_jam_operasional').value = row.jam_operasional || '';
            document.getElementById('edit_hari_operasional').value = row.hari_operasional || '';
            document.getElementById('edit_harga_tiket').value = row.harga_tiket || '0';
            // Check fasilitas
            if (row.fasilitas_ids) {
                $('#edit_fasilitas_ids').val(row.fasilitas_ids).trigger('change');
            } else {
                $('#edit_fasilitas_ids').val(null).trigger('change');
            }

            document.getElementById('edit_aturan').value = row.aturan || '';
            document.getElementById('edit_latitude').value = row.latitude || '';
            document.getElementById('edit_longitude').value = row.longitude || '';

            // Re-center and add marker to mapPickerEdit
            setTimeout(() => {
                if (mapEdit) {
                    mapEdit.invalidateSize();
                    let lat = parseFloat(row.latitude) || -5.42544;
                    let lng = parseFloat(row.longitude) || 105.25804;
                    mapEdit.setView([lat, lng], 14);
                    if (markerEdit) mapEdit.removeLayer(markerEdit);
                    if (row.latitude && row.longitude) {
                        markerEdit = L.marker([lat, lng]).addTo(mapEdit);
                    }
                }
            }, 300);

            var editModal = new bootstrap.Modal(document.getElementById('modalEdit'));
            editModal.show();
        };

        // --- LEAFLET MAP PICKER LOGIC ---
        let mapTambah = null;
        let markerTambah = null;
        let mapEdit = null;
        let markerEdit = null;

        const defaultLat = -5.42544;
        const defaultLng = 105.25804;

        function initMap(mapId, latInputId, lngInputId) {
            const map = L.map(mapId).setView([defaultLat, defaultLng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            let marker = null;

            // Click on map to set marker & update inputs
            map.on('click', function(e) {
                if (marker) map.removeLayer(marker);
                marker = L.marker(e.latlng).addTo(map);
                document.getElementById(latInputId).value = e.latlng.lat.toFixed(6);
                document.getElementById(lngInputId).value = e.latlng.lng.toFixed(6);

                // Reverse geocoding via Nominatim API
                let addressInputId = latInputId === 'latitude' ? 'alamat' : 'edit_alamat';
                let addressInput = document.getElementById(addressInputId);

                if (addressInput) {
                    addressInput.value = "Sedang mengambil alamat...";
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.display_name) {
                                addressInput.value = data.display_name;
                            } else {
                                addressInput.value = "";
                            }
                        }).catch(err => {
                            addressInput.value = "";
                            console.error(err);
                        });
                }
            });

            // Input change to update map marker
            const latInput = document.getElementById(latInputId);
            const lngInput = document.getElementById(lngInputId);

            const updateMapFromInput = () => {
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    if (marker) map.removeLayer(marker);
                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 16);
                }
            };

            latInput.addEventListener('input', updateMapFromInput);
            lngInput.addEventListener('input', updateMapFromInput);

            return {
                map,
                getMarker: () => marker,
                setMarker: (m) => marker = m
            };
        }

        // Init maps on Modal shown to ensure correct dimensions
        document.getElementById('modalTambah').addEventListener('shown.bs.modal', function() {
            if (!mapTambah) {
                const mapObj = initMap('mapPickerTambah', 'latitude', 'longitude');
                mapTambah = mapObj.map;
                markerTambah = mapObj.getMarker();
            } else {
                mapTambah.invalidateSize();
            }
        });

        document.getElementById('modalEdit').addEventListener('shown.bs.modal', function() {
            if (!mapEdit) {
                const mapObj = initMap('mapPickerEdit', 'edit_latitude', 'edit_longitude');
                mapEdit = mapObj.map;
                markerEdit = mapObj.getMarker();
            } else {
                mapEdit.invalidateSize();
            }
        });

        // Locate Me Handler
        function handleLocateMe(btnId, mapInstance, latInputId, lngInputId) {
            document.getElementById(btnId).addEventListener('click', function() {
                if (!mapInstance) return;
                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                mapInstance.locate({
                    setView: true,
                    maxZoom: 16
                });

                mapInstance.once('locationfound', function(e) {
                    btn.innerHTML = originalText;
                    document.getElementById(latInputId).value = e.latlng.lat.toFixed(6);
                    document.getElementById(lngInputId).value = e.latlng.lng.toFixed(6);

                    // Fire input event to update marker automatically
                    document.getElementById(latInputId).dispatchEvent(new Event('input'));

                    // Reverse geocoding
                    let addressInputId = latInputId === 'latitude' ? 'alamat' : 'edit_alamat';
                    let addressInput = document.getElementById(addressInputId);

                    if (addressInput) {
                        addressInput.value = "Sedang mengambil alamat...";
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.display_name) {
                                    addressInput.value = data.display_name;
                                } else {
                                    addressInput.value = "";
                                }
                            }).catch(err => {
                                addressInput.value = "";
                            });
                    }
                });

                mapInstance.once('locationerror', function(e) {
                    btn.innerHTML = originalText;
                    alert("Gagal mengakses lokasi Anda.");
                });
            });
        }

        // Attach Locate Me handlers on modal shown or just setup listeners safely
        document.getElementById('modalTambah').addEventListener('shown.bs.modal', function() {
            handleLocateMe('btn-locate-tambah', mapTambah, 'latitude', 'longitude');
        }, {
            once: true
        });

        document.getElementById('modalEdit').addEventListener('shown.bs.modal', function() {
            handleLocateMe('btn-locate-edit', mapEdit, 'edit_latitude', 'edit_longitude');
        }, {
            once: true
        });

        // Initialize Select2 for Fasilitas
        function formatFasilitas(state) {
            if (!state.id) {
                return state.text;
            }
            var icon = $(state.element).data('icon');
            var $state = $(
                '<span><i class="bi ' + (icon ? icon : '') + ' me-1"></i> ' + state.text + '</span>'
            );
            return $state;
        }

        $('#fasilitas_ids').select2({
            theme: 'bootstrap-5',
            templateResult: formatFasilitas,
            templateSelection: formatFasilitas,
            dropdownParent: $('#modalTambah')
        });

        $('#edit_fasilitas_ids').select2({
            theme: 'bootstrap-5',
            templateResult: formatFasilitas,
            templateSelection: formatFasilitas,
            dropdownParent: $('#modalEdit')
        });

        // ================= ANALYTICS CHARTS =================
        const priceCounts = {
            'Gratis': 0,
            '< Rp 20rb': 0,
            'Rp 20rb - 50rb': 0,
            '> Rp 50rb': 0
        };
        const katCounts = {};

        tableData.forEach(d => {
            // Price Range
            let price = parseFloat(d.harga_tiket || 0);
            if (price === 0) priceCounts['Gratis']++;
            else if (price < 20000) priceCounts['< Rp 20rb']++;
            else if (price <= 50000) priceCounts['Rp 20rb - 50rb']++;
            else priceCounts['> Rp 50rb']++;

            // Kategori
            let katName = d.nama_kategori || 'Tanpa Kategori';
            katCounts[katName] = (katCounts[katName] || 0) + 1;
        });

        // Price Chart (Bar)
        if (document.querySelector("#chart-harga-tiket")) {
            new ApexCharts(document.querySelector("#chart-harga-tiket"), {
                series: [{
                    name: 'Jumlah Destinasi',
                    data: Object.values(priceCounts)
                }],
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        distributed: true,
                        columnWidth: '50%'
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -20,
                    style: {
                        colors: ["#304758"]
                    }
                },
                colors: ['#198754', '#0dcaf0', '#ffc107', '#dc3545'],
                xaxis: {
                    categories: Object.keys(priceCounts)
                },
                legend: {
                    show: false
                }
            }).render();
        }

        // Kategori Chart (Donut)
        if (document.querySelector("#chart-kategori-destinasi")) {
            new ApexCharts(document.querySelector("#chart-kategori-destinasi"), {
                series: Object.values(katCounts),
                labels: Object.keys(katCounts),
                chart: {
                    type: 'donut',
                    height: 250
                },
                theme: {
                    palette: 'palette2'
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%'
                        }
                    }
                },
                legend: {
                    position: 'right'
                }
            }).render();
        }
    });
</script>
<?= $this->endSection() ?>