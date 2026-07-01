<?php
/* ======================================================
   VIEW KATEGORI (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna. Di sini, View akan menyusun data
   kategori wisata ke dalam tabel yang rapi.
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('title') ?>
Manajemen Kategori Wisata
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Kategori</li>
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
                <h3>Terbaru</h3>
                <p><?= !empty($kategori) ? esc($kategori[0]['nama_kategori']) : '-' ?></p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z"></path>
                <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z"></path>
            </svg>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h3 class="card-title m-0">Daftar Kategori Wisata</h3>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </button>
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
                <input type="text" id="table-filter" class="form-control form-control-sm" placeholder="Cari kategori...">
            </div>
        </div>

        <div id="kategori-table"></div>
    </div>
</div>

<!-- ======================================================
     MODAL TAMBAH (CREATE)
     
     Ini adalah formulir (form) popup yang muncul saat tombol "Tambah" diklik.
     Form ini memiliki atribut action=".../admin/kategori/store".
     Artinya, saat admin mengklik tombol "Simpan", semua kotak isian (input) di bawah ini
     akan dibungkus dan dikirimkan ke dalam Controller fungsi 'store()'.
     
     Kolom Isian (Input) yang ada di form ini:
     - Nama Kategori      (Wajib)
====================================================== -->
<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/kategori/store') ?>" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="modal-header text-bg-primary">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Kategori Wisata</h5>
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
     MODAL UBAH (UPDATE)
     
     Ini adalah formulir popup untuk mengedit data Kategori.
     Awalnya form ini kosong. Namun berkat bantuan JavaScript (di bagian bawah file ini),
     saat admin mengklik tombol "Edit" kuning di tabel, JavaScript akan menarik 
     data baris tersebut dan mengisikannya otomatis ke dalam form edit ini.
     
     Saat tombol "Update" diklik, data baru akan dikirimkan ke 
     Controller fungsi 'update()' beserta ID Kategori tersebut.
     
     Kolom Isian (Input) yang ada di form ini:
     - Nama Kategori      (Wajib)
====================================================== -->
<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEdit" action="" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header text-bg-warning">
                    <h5 class="modal-title" id="modalEditLabel">Edit Kategori Wisata</h5>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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

        // Data for Tabulator
        const tableData = <?= json_encode($kategori) ?>;

        const actionFormatter = (cell) => {
            const row = cell.getRow().getData();
            const editUrl = `<?= base_url('admin/kategori/update') ?>/${row.id}`;
            const deleteUrl = `<?= base_url('admin/kategori/delete') ?>/${row.id}`;

            return `
            <button class="btn btn-warning btn-sm" onclick="editData(${row.id}, '${row.nama_kategori}')"><i class="bi bi-pencil"></i></button>
            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('${deleteUrl}')"><i class="bi bi-trash"></i></button>
        `;
        };

        const table = new Tabulator("#kategori-table", {
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
                    title: "Nama Kategori",
                    field: "nama_kategori",
                    headerFilter: "input"
                },
                {
                    title: "Slug",
                    field: "slug"
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
                        field: 'nama_kategori',
                        type: 'like',
                        value: value
                    }]
                ]);
            } else {
                table.clearFilter();
            }
        });

        document.getElementById('export-csv').addEventListener('click', () => table.download('csv', 'kategori.csv'));
        document.getElementById('export-json').addEventListener('click', () => table.download('json', 'kategori.json'));
        document.getElementById('print-table').addEventListener('click', () => table.print(false, true));

        // Global editData function accessible from inline onclick
        window.editData = function(id, nama) {
            document.getElementById('formEdit').action = `<?= base_url('admin/kategori/update') ?>/${id}`;
            document.getElementById('edit_nama_kategori').value = nama;
            var editModal = new bootstrap.Modal(document.getElementById('modalEdit'));
            editModal.show();
        };
    });
</script>
<?= $this->endSection() ?>