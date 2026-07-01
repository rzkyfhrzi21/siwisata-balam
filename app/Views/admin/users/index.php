<?php
/* ======================================================
   VIEW MANAJEMEN ADMIN (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna. 
   
   Tampilan visual untuk mengelola akun administrator.
   Termasuk fitur grafis "Ranking Aktivitas Admin" yang memotivasi admin
   karena mereka bisa melihat siapa yang paling sering login dan bekerja.
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('title') ?>
Manajemen Admin
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Users</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Professional Small Boxes -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3><?= count($users) ?></h3>
                <p>Total Administrator Sistem</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- ANALYTICS CHARTS ROW -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card shadow h-100">
            <div class="card-header border-0">
                <h3 class="card-title">Ranking Aktivitas Admin</h3>
            </div>
            <div class="card-body">
                <?php if(!empty($userActivity)): ?>
                    <div id="chart-user-activity"></div>
                <?php else: ?>
                    <div class="alert alert-light text-center border">Belum ada data log aktivitas.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title m-0">Daftar Admin</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg"></i> Tambah Admin
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
                <input type="text" id="table-filter" class="form-control form-control-sm" placeholder="Cari admin...">
            </div>
        </div>
        
        <div id="users-table"></div>
    </div>
</div>

<!-- ======================================================
     MODAL TAMBAH (CREATE)
     
     Ini adalah formulir (form) popup yang muncul saat tombol "Tambah" diklik.
     Form ini memiliki atribut action=".../admin/users/store".
     Artinya, saat admin mengklik tombol "Simpan", semua kotak isian (input) di bawah ini
     akan dibungkus dan dikirimkan ke dalam Controller fungsi 'store()'.
     
     Kolom Isian (Input) yang ada di form ini:
     - Nama Lengkap       (Wajib)
     - Foto Profil        (Tidak Wajib)
     - Username           (Wajib)
     - Email              (Tidak Wajib)
     - WhatsApp           (Tidak Wajib)
     - Instagram          (Tidak Wajib)
     - Password           (Wajib, min 5 karakter)
     - Konfirmasi Password(Wajib, disamakan)
====================================================== -->
<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/users/store') ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="modal-header text-bg-primary">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Admin Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                        <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label for="foto_profil" class="form-label">Foto Profil</label>
                        <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*">
                        <div class="form-text">Maksimal 2MB (JPG/PNG).</div>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div class="invalid-feedback">Username wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="whatsapp" class="form-label">WhatsApp</label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" placeholder="628xxx">
                    </div>
                    <div class="mb-3">
                        <label for="instagram" class="form-label">Instagram</label>
                        <input type="text" class="form-control" id="instagram" name="instagram" placeholder="tanpa @">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" minlength="5" required>
                        <div class="invalid-feedback">Password minimal 5 karakter.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="5" required>
                        <div class="invalid-feedback">Konfirmasi password wajib diisi.</div>
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
     
     Ini adalah formulir popup untuk mengedit data Admin.
     Awalnya form ini kosong. Namun berkat bantuan JavaScript (di bagian bawah file ini),
     saat admin mengklik tombol "Edit" kuning di tabel, JavaScript akan menarik 
     data baris tersebut dan mengisikannya otomatis ke dalam form edit ini.
     
     Saat tombol "Update" diklik, data baru akan dikirimkan ke 
     Controller fungsi 'update()' beserta ID Admin tersebut.
     
     Kolom Isian (Input) mirip dengan form Tambah, dengan catatan:
     - Foto Profil        (Boleh kosong jika tidak diubah)
     - Password Baru      (Boleh kosong jika tidak ingin mengubah password lama)
====================================================== -->
<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEdit" action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header text-bg-warning">
                    <h5 class="modal-title" id="modalEditLabel">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_foto_profil" class="form-label">Foto Profil (Biarkan kosong jika tidak diubah)</label>
                        <input type="file" class="form-control" id="edit_foto_profil" name="foto_profil" accept="image/*">
                        <div class="form-text">Maksimal 2MB (JPG/PNG).</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_username" name="username" required>
                        <div class="invalid-feedback">Username wajib diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="edit_whatsapp" class="form-label">WhatsApp</label>
                        <input type="text" class="form-control" id="edit_whatsapp" name="whatsapp" placeholder="628xxx">
                    </div>
                    <div class="mb-3">
                        <label for="edit_instagram" class="form-label">Instagram</label>
                        <input type="text" class="form-control" id="edit_instagram" name="instagram" placeholder="tanpa @">
                    </div>
                    <hr>
                    <div class="alert alert-info py-2 fs-7">
                        <i class="bi bi-info-circle me-1"></i> Biarkan kosong jika tidak ingin mengubah password.
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="edit_password" name="password" minlength="5">
                        <div class="invalid-feedback">Password minimal 5 karakter jika diubah.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password_confirm" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="edit_password_confirm" name="password_confirm" minlength="5">
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
document.addEventListener('DOMContentLoaded', function () {
    // Validation
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                // Check if passwords match
                const pass = form.querySelector('input[name="password"]');
                const passConfirm = form.querySelector('input[name="password_confirm"]');
                if (pass && passConfirm && pass.value !== passConfirm.value) {
                    passConfirm.setCustomValidity("Password tidak cocok");
                } else if (passConfirm) {
                    passConfirm.setCustomValidity("");
                }

                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        });

    const tableData = <?= json_encode($users) ?>;
    const currentUserId = <?= session()->get('id') ?? '0' ?>;

    const actionFormatter = (cell) => {
        const row = cell.getRow().getData();
        const deleteUrl = `<?= base_url('admin/users/delete') ?>/${row.id}`;
        
        let deleteBtn = `<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('${deleteUrl}')"><i class="bi bi-trash"></i></button>`;
        if (row.id == currentUserId) {
            deleteBtn = `<button type="button" class="btn btn-secondary btn-sm" disabled title="Anda tidak dapat menghapus akun sendiri"><i class="bi bi-trash"></i></button>`;
            
            return `
                <a href="<?= base_url('admin/profil') ?>" class="btn btn-primary btn-sm" title="Edit Profil Anda"><i class="bi bi-person-fill"></i></a>
                ${deleteBtn}
            `;
        }

        return `
            <button class="btn btn-warning btn-sm" onclick="editData(${row.id}, '${row.nama}', '${row.username}', '${row.email || ''}', '${row.whatsapp || ''}', '${row.instagram || ''}')"><i class="bi bi-pencil"></i></button>
            ${deleteBtn}
        `;
    };

    const usernameFormatter = (cell) => {
        const val = cell.getValue();
        return `<span class="badge text-bg-primary">${val}</span>`;
    };

    const table = new Tabulator("#users-table", {
        data: tableData,
        layout: "fitColumns",
        pagination: "local",
        paginationSize: 10,
        paginationSizeSelector: [10, 25, 50, 100],
        columns: [
            { title: "No", field: "id", width: 60, headerSort: false, formatter: "rownum" },
            { title: "Nama Lengkap", field: "nama", headerFilter: "input" },
            { title: "Username", field: "username", formatter: usernameFormatter, headerFilter: "input" },
            { title: "Email", field: "email", headerFilter: "input" },
            { title: "WhatsApp", field: "whatsapp", headerFilter: "input" },
            { title: "Aksi", headerSort: false, formatter: actionFormatter, width: 120, hozAlign: "center" }
        ],
    });

    document.getElementById('table-filter').addEventListener('input', (e) => {
        const value = e.target.value;
        if (value) {
            table.setFilter([
                [{ field: 'nama', type: 'like', value: value },
                 { field: 'username', type: 'like', value: value }]
            ]);
        } else {
            table.clearFilter();
        }
    });

    document.getElementById('export-csv').addEventListener('click', () => table.download('csv', 'users.csv'));
    document.getElementById('export-json').addEventListener('click', () => table.download('json', 'users.json'));
    document.getElementById('print-table').addEventListener('click', () => table.print(false, true));

    window.editData = function(id, nama, username, email, whatsapp, instagram) {
        document.getElementById('formEdit').action = `<?= base_url('admin/users/update') ?>/${id}`;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_whatsapp').value = whatsapp;
        document.getElementById('edit_instagram').value = instagram;
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirm').value = '';
        
        var editModal = new bootstrap.Modal(document.getElementById('modalEdit'));
        editModal.show();
    };

    // ================= ANALYTICS CHARTS =================
    const userActivityData = <?= isset($userActivity) ? json_encode($userActivity) : '[]' ?>;
    if (userActivityData.length > 0 && document.querySelector("#chart-user-activity")) {
        // Sort descending
        userActivityData.sort((a,b) => b.count - a.count);
        
        var userOptions = {
            series: [{ name: 'Total Tindakan (Log)', data: userActivityData.map(u => u.count) }],
            chart: { type: 'bar', height: 250, toolbar: { show: false } },
            plotOptions: { bar: { borderRadius: 4, distributed: true, dataLabels: { position: 'top' } } },
            colors: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0'],
            dataLabels: { enabled: true, offsetY: -20, style: { fontSize: '12px', colors: ["#304758"] } },
            xaxis: { categories: userActivityData.map(u => u.nama) },
            legend: { show: false }
        };
        new ApexCharts(document.querySelector("#chart-user-activity"), userOptions).render();
    }
});
</script>
<?= $this->endSection() ?>
