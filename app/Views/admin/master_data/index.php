<?php
/* ======================================================
   VIEW MASTER DATA (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna. 
   
   Berbeda dengan View Destinasi yang hanya menampilkan 1 jenis tabel, 
   View ini merakit DUA KERANJANG DATA sekaligus (variabel $kategori dan $fasilitas)
   yang dikirim oleh MasterDataController. 
   Tampilannya akan memiliki 2 grafik dan 2 tabel yang berbeda dalam satu layar.
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('title') ?>
Master Data (Kategori & Fasilitas)
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Master Data</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Professional Small Boxes -->
<div class="row mb-4">
    <div class="col-lg-6 col-md-6">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3><?= count($kategori) ?></h3>
                <p>Total Kategori</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"></path>
            </svg>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="small-box text-bg-info">
            <div class="inner">
                <h3><?= count($fasilitas) ?></h3>
                <p>Total Fasilitas</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M5.25 12h13.5m-13.5 0v7.5A1.5 1.5 0 006.75 21h10.5a1.5 1.5 0 001.5-1.5V12m-13.5 0V7.5A1.5 1.5 0 016.75 6h10.5a1.5 1.5 0 011.5 1.5V12m-13.5 0h13.5"></path>
            </svg>
        </div>
    </div>
</div>

<!-- ANALYTICS CHARTS ROW -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header border-0">
                <h3 class="card-title">Kepadatan Kategori Wisata</h3>
            </div>
            <div class="card-body">
                <div id="chart-kategori"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header border-0">
                <h3 class="card-title">Ketersediaan Fasilitas</h3>
            </div>
            <div class="card-body">
                <div id="chart-fasilitas"></div>
            </div>
        </div>
    </div>
</div>

<!-- KATEGORI WISATA CARD -->
<div class="card shadow mb-4">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title m-0 fw-bold"><i class="bi bi-tags text-primary me-2"></i> Kategori Wisata</h3>
        <div class="ms-auto d-flex align-items-center">
            <button type="button" class="btn btn-tool text-secondary btn-sm" data-lte-toggle="card-collapse">
                <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
            </button>
            <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                <i class="bi bi-plus-lg"></i> Tambah Kategori
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3 align-items-center">
            <div class="d-flex gap-2">
                <button id="export-csv-kategori" class="btn btn-outline-secondary btn-sm"><i class="bi bi-filetype-csv me-1"></i> Export CSV</button>
                <button id="export-json-kategori" class="btn btn-outline-secondary btn-sm"><i class="bi bi-filetype-json me-1"></i> Export JSON</button>
                <button id="print-table-kategori" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer me-1"></i> Print</button>
            </div>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="filter-kategori" class="form-control form-control-sm" placeholder="Cari kategori...">
            </div>
        </div>
        <div id="kategori-table"></div>
    </div>
</div>

<!-- FASILITAS WISATA CARD -->
<div class="card shadow mb-4">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title m-0 fw-bold"><i class="bi bi-box-seam text-info me-2"></i> Fasilitas Wisata</h3>
        <div class="ms-auto d-flex align-items-center">
            <button type="button" class="btn btn-tool text-secondary btn-sm" data-lte-toggle="card-collapse">
                <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
            </button>
            <button class="btn btn-info text-white btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#modalTambahFasilitas">
                <i class="bi bi-plus-lg"></i> Tambah Fasilitas
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3 align-items-center">
            <div class="d-flex gap-2">
                <button id="export-csv-fasilitas" class="btn btn-outline-secondary btn-sm"><i class="bi bi-filetype-csv me-1"></i> Export CSV</button>
                <button id="export-json-fasilitas" class="btn btn-outline-secondary btn-sm"><i class="bi bi-filetype-json me-1"></i> Export JSON</button>
                <button id="print-table-fasilitas" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer me-1"></i> Print</button>
            </div>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="filter-fasilitas" class="form-control form-control-sm" placeholder="Cari fasilitas...">
            </div>
        </div>
        <div id="fasilitas-table"></div>
    </div>
</div>

<!-- ======================================================
     BAGIAN MODAL KATEGORI (CREATE & UPDATE)
     Ini adalah kerangka popup form untuk menambah/mengedit Kategori.
     Saat tombol "Simpan" diklik, form akan mengirim data (action=".../store")
     menuju ke KategoriController (Bukan MasterDataController).
====================================================== -->


<!-- ================= MODALS KATEGORI ================= -->
<!-- ======================================================
     MODAL TAMBAH KATEGORI (CREATE)
     
     Ini adalah formulir popup untuk menambah kategori baru.
     
     Kolom Isian (Input) yang ada di form ini:
     - Nama Kategori      (Wajib)
====================================================== -->
<div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/kategori/store') ?>" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="modal-header text-bg-primary">
                    <h5 class="modal-title" id="modalTambahKategoriLabel">Tambah Kategori Wisata</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
                        <div class="invalid-feedback">Nama kategori wajib diisi.</div>
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
     MODAL UBAH KATEGORI (UPDATE)
     
     Ini adalah formulir popup untuk mengedit kategori.
     
     Kolom Isian (Input) yang ada di form ini:
     - Nama Kategori      (Wajib)
====================================================== -->
<div class="modal fade" id="modalEditKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditKategori" action="" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header text-bg-warning">
                    <h5 class="modal-title">Edit Kategori Wisata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_kategori" name="nama_kategori" required>
                        <div class="invalid-feedback">Nama kategori wajib diisi.</div>
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
     BAGIAN MODAL FASILITAS (CREATE & UPDATE)
     Sama seperti Kategori, popup ini akan mengirim data ke
     FasilitasController. Terdapat tambahan isian 'icon' (opsional)
     untuk mengatur logo kecil (seperti lambang WiFi).
====================================================== -->
<!-- ================= MODALS FASILITAS ================= -->
<!-- ======================================================
     MODAL TAMBAH FASILITAS (CREATE)
     
     Ini adalah formulir popup untuk menambah fasilitas baru.
     
     Kolom Isian (Input) yang ada di form ini:
     - Nama Fasilitas     (Wajib)
     - Ikon Bootstrap     (Tidak Wajib, contoh: bi-wifi)
====================================================== -->
<div class="modal fade" id="modalTambahFasilitas" tabindex="-1" aria-labelledby="modalTambahFasilitasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/fasilitas/store') ?>" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="modal-header text-bg-info text-white">
                    <h5 class="modal-title" id="modalTambahFasilitasLabel">Tambah Fasilitas Wisata</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_fasilitas" class="form-label">Nama Fasilitas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_fasilitas" name="nama_fasilitas" required>
                        <div class="invalid-feedback">Nama fasilitas wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label">Kode Icon (Opsional)</label>
                        <input type="text" class="form-control" id="icon" name="icon" placeholder="bi-wifi">
                        <div class="form-text">Gunakan penamaan class Bootstrap Icons (cth: bi-wifi, bi-cup-hot, bi-p-circle). Referensi: <a href="https://icons.getbootstrap.com/" target="_blank">icons.getbootstrap.com</a></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================
     MODAL UBAH FASILITAS (UPDATE)
     
     Ini adalah formulir popup untuk mengedit fasilitas.
     
     Kolom Isian (Input) yang ada di form ini:
     - Nama Fasilitas     (Wajib)
     - Ikon Bootstrap     (Tidak Wajib, contoh: bi-wifi)
====================================================== -->
<div class="modal fade" id="modalEditFasilitas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditFasilitas" action="" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header text-bg-warning">
                    <h5 class="modal-title">Edit Fasilitas Wisata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_fasilitas" class="form-label">Nama Fasilitas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_fasilitas" name="nama_fasilitas" required>
                        <div class="invalid-feedback">Nama fasilitas wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_icon" class="form-label">Kode Icon</label>
                        <input type="text" class="form-control" id="edit_icon" name="icon">
                        <div class="form-text">Cth: bi-wifi. Kosongkan jika tidak perlu.</div>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validation
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        });

        // ================= KATEGORI TABLE =================
        const dataKategori = <?= json_encode($kategori) ?>;

        const actionKategoriFormatter = (cell) => {
            const row = cell.getRow().getData();
            const deleteUrl = `<?= base_url('admin/kategori/delete') ?>/${row.id}`;
            return `
            <button class="btn btn-warning btn-sm" onclick="editKategori(${row.id}, '${row.nama_kategori}')"><i class="bi bi-pencil"></i></button>
            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('${deleteUrl}')"><i class="bi bi-trash"></i></button>
        `;
        };

        const tableKat = new Tabulator("#kategori-table", {
            data: dataKategori,
            layout: "fitColumns",
            pagination: "local",
            paginationSize: 5,
            paginationSizeSelector: [5, 10, 25],
            columns: [{
                    title: "No",
                    field: "id",
                    width: 60,
                    headerSort: false,
                    formatter: "rownum"
                },
                {
                    title: "Nama Kategori",
                    field: "nama_kategori"
                },
                {
                    title: "Slug",
                    field: "slug"
                },
                {
                    title: "Jumlah Wisata",
                    field: "jumlah_wisata",
                    hozAlign: "center"
                },
                {
                    title: "Aksi",
                    headerSort: false,
                    formatter: actionKategoriFormatter,
                    width: 120,
                    hozAlign: "center"
                }
            ],
        });

        document.getElementById('filter-kategori').addEventListener('input', (e) => {
            if (e.target.value) {
                tableKat.setFilter('nama_kategori', 'like', e.target.value);
            } else {
                tableKat.clearFilter();
            }
        });

        // ================= FASILITAS TABLE =================
        const dataFasilitas = <?= json_encode($fasilitas) ?>;

        const actionFasilitasFormatter = (cell) => {
            const row = cell.getRow().getData();
            const deleteUrl = `<?= base_url('admin/fasilitas/delete') ?>/${row.id}`;
            return `
            <button class="btn btn-warning btn-sm" onclick="editFasilitas(${row.id}, '${row.nama_fasilitas}', '${row.icon || ''}')"><i class="bi bi-pencil"></i></button>
            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('${deleteUrl}')"><i class="bi bi-trash"></i></button>
        `;
        };

        const iconFormatter = (cell) => {
            const val = cell.getValue();
            if (val) return `<i class="bi ${val} fs-5"></i> <span class="ms-1">${val}</span>`;
            return '-';
        }

        const tableFas = new Tabulator("#fasilitas-table", {
            data: dataFasilitas,
            layout: "fitColumns",
            pagination: "local",
            paginationSize: 10,
            paginationSizeSelector: [10, 25, 50],
            columns: [{
                    title: "No",
                    field: "id",
                    width: 60,
                    headerSort: false,
                    formatter: "rownum"
                },
                {
                    title: "Nama Fasilitas",
                    field: "nama_fasilitas"
                },
                {
                    title: "Slug",
                    field: "slug"
                },
                {
                    title: "Icon",
                    field: "icon",
                    formatter: iconFormatter
                },
                {
                    title: "Jumlah Wisata",
                    field: "jumlah_wisata",
                    hozAlign: "center"
                },
                {
                    title: "Aksi",
                    headerSort: false,
                    formatter: actionFasilitasFormatter,
                    width: 120,
                    hozAlign: "center"
                }
            ],
        });

        document.getElementById('filter-fasilitas').addEventListener('input', (e) => {
            if (e.target.value) {
                tableFas.setFilter('nama_fasilitas', 'like', e.target.value);
            } else {
                tableFas.clearFilter();
            }
        });

        // Global Functions
        window.editKategori = function(id, nama) {
            document.getElementById('formEditKategori').action = `<?= base_url('admin/kategori/update') ?>/${id}`;
            document.getElementById('edit_nama_kategori').value = nama;
            new bootstrap.Modal(document.getElementById('modalEditKategori')).show();
        };

        window.editFasilitas = function(id, nama, icon) {
            document.getElementById('formEditFasilitas').action = `<?= base_url('admin/fasilitas/update') ?>/${id}`;
            document.getElementById('edit_nama_fasilitas').value = nama;
            document.getElementById('edit_icon').value = icon;
            new bootstrap.Modal(document.getElementById('modalEditFasilitas')).show();
        };

        // ================= ANALYTICS CHARTS =================
        // Prepare Data for Kategori Chart
        const katLabels = dataKategori.map(k => k.nama_kategori);
        const katData = dataKategori.map(k => k.jumlah_wisata);

        var katOptions = {
            series: [{
                name: 'Total Wisata',
                data: katData
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
                    dataLabels: {
                        position: 'top'
                    },
                    columnWidth: '40%'
                }
            },
            colors: ['#0dcaf0'],
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: katLabels,
                position: 'bottom',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                tooltip: {
                    enabled: true
                }
            }
        };
        new ApexCharts(document.querySelector("#chart-kategori"), katOptions).render();

        // Prepare Data for Fasilitas Chart
        // Sort dataFasilitas by jumlah_wisata desc for better visualization
        const sortedFas = [...dataFasilitas].sort((a, b) => b.jumlah_wisata - a.jumlah_wisata).slice(0, 10);
        const fasLabels = sortedFas.map(f => f.nama_fasilitas);
        const fasData = sortedFas.map(f => f.jumlah_wisata);

        var fasOptions = {
            series: [{
                name: 'Tersedia di Wisata',
                data: fasData
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
                    horizontal: true,
                    borderRadius: 4,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            colors: ['#ffc107'],
            dataLabels: {
                enabled: true,
                offsetX: 20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: fasLabels
            }
        };
        new ApexCharts(document.querySelector("#chart-fasilitas"), fasOptions).render();
    });
</script>
<?= $this->endSection() ?>