<?php
/* ======================================================
   VIEW PROFIL (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna. 
   
   Tampilan layar khusus agar admin bisa melihat data dirinya 
   sendiri dan mengganti password jika diperlukan.
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('title') ?>
Profil Admin
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Profil</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Professional Small Boxes -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="small-box text-bg-info">
            <div class="inner">
                <h3>Administrator Aktif</h3>
                <p>Status Profil</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"></path>
            </svg>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Profile sidebar -->
    <div class="col-md-4">
        <!-- About card -->
        <div class="card shadow">
            <div class="card-body text-center">
                <?php if (session()->get('foto_profil')): ?>
                    <img src="<?= base_url('uploads/profil/' . session()->get('foto_profil')) ?>" alt="User profile picture" class="profile-user-img img-fluid img-circle mb-3 shadow-sm" style="width: 96px; height: 96px; object-fit: cover; border-radius: 50%;">
                <?php else: ?>
                    <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 96px; height: 96px; font-size: 2rem; border-radius: 50%;" aria-hidden="true">
                        <?= strtoupper(substr(session()->get('nama') ?? 'AD', 0, 2)) ?>
                    </div>
                <?php endif; ?>
                <h3 class="h5 mb-0"><?= esc(session()->get('nama')) ?></h3>
                <p class="text-secondary mb-3">Sistem Administrator</p>
                <ul class="list-group list-group-flush text-start small">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-secondary">Username</span>
                        <span class="fw-semibold text-primary"><?= esc(session()->get('username')) ?></span>
                    </li>
                </ul>
            </div>
        </div>
        <!-- About details -->
        <div class="card mt-3 shadow">
            <div class="card-header">
                <h3 class="card-title">Tentang Saya</h3>
            </div>
            <div class="card-body">
                <p class="fw-bold mb-1">
                    <i class="bi bi-mortarboard me-1 text-secondary" aria-hidden="true"></i>
                    Role
                </p>
                <p class="text-secondary mb-3">
                    Administrator<br>
                    <?= esc(session()->get('nama')) ?>
                </p>
                <p class="fw-bold mb-1">
                    <i class="bi bi-envelope me-1 text-secondary" aria-hidden="true"></i>
                    Kontak
                </p>
                <p class="text-secondary mb-3">
                    <i class="bi bi-envelope-at me-1"></i> 
                    <?php if (session()->get('email')): ?>
                        <a href="mailto:<?= esc(session()->get('email')) ?>" target="_blank" class="text-decoration-none"><?= esc(session()->get('email')) ?></a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                    <br>

                    <i class="bi bi-whatsapp me-1"></i> 
                    <?php if (session()->get('whatsapp')): ?>
                        <a href="https://wa.me/<?= esc(session()->get('whatsapp')) ?>" target="_blank" class="text-decoration-none"><?= esc(session()->get('whatsapp')) ?></a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                    <br>

                    <i class="bi bi-instagram me-1"></i> 
                    <?php if (session()->get('instagram')): ?>
                        <a href="https://instagram.com/<?= esc(session()->get('instagram')) ?>" target="_blank" class="text-decoration-none">@<?= esc(session()->get('instagram')) ?></a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </p>
                <div class="alert alert-warning p-2 m-0 fs-7 mt-3">
                    <i class="bi bi-exclamation-triangle"></i> Jika Anda mengubah password, Anda akan diarahkan untuk login ulang.
                </div>
            </div>
        </div>
    </div>

    <!-- Tabbed content -->
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="profile-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-selected="true">
                            Edit Profil
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Settings tab -->
                    <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="settings-tab">
<!-- ======================================================
     FORM UBAH PROFIL (UPDATE)
     
     Ini adalah formulir untuk mengedit data diri admin yang sedang login.
     
     Kolom Isian (Input) yang ada di form ini:
     - Nama Lengkap       (Wajib)
     - Foto Profil        (Tidak Wajib)
     - Username           (Wajib)
     - Email              (Tidak Wajib)
     - WhatsApp           (Tidak Wajib)
     - Instagram          (Tidak Wajib)
     - Password Baru      (Boleh kosong jika tidak ingin mengubah password lama)
     - Konfirmasi Password(Wajib jika Password Baru diisi)
====================================================== -->
                        <form action="<?= base_url('admin/profil/update') ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <?= csrf_field() ?>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3">Informasi Pribadi</h5>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="foto_profil">Foto Profil</label>
                                    <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*">
                                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto profil.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="<?= esc(session()->get('nama')) ?>" required>
                                    <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?= esc(session()->get('username')) ?>" required>
                                    <div class="invalid-feedback">Username wajib diisi.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= esc(session()->get('email')) ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="whatsapp">WhatsApp</label>
                                    <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?= esc(session()->get('whatsapp')) ?>" placeholder="628xxx">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="instagram">Instagram</label>
                                    <input type="text" class="form-control" id="instagram" name="instagram" value="<?= esc(session()->get('instagram')) ?>" placeholder="tanpa @">
                                </div>

                                <div class="col-md-12 mt-4">
                                    <h5 class="border-bottom pb-2 mb-3">Keamanan <span class="fs-6 text-muted fw-normal">(Opsional)</span></h5>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="password">Password Baru</label>
                                    <input type="password" class="form-control" id="password" name="password" minlength="5">
                                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah password.</div>
                                    <div class="invalid-feedback">Password minimal 5 karakter.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="password_confirm">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="5">
                                </div>

                                <div class="col-12 mt-4 text-end">
                                    <button type="reset" class="btn btn-outline-secondary me-2">Batal</button>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    // Check if passwords match
                    const pass = document.getElementById('password');
                    const passConfirm = document.getElementById('password_confirm');
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
    });
</script>
<?= $this->endSection() ?>
