<?php
/* ======================================================
   VIEW GALERI (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna. 
   
   Tampilan ini agak spesial karena data tempat wisata ditampilkan
   mengelompok berdasarkan Kategorinya. Terdapat tombol khusus untuk 
   memunculkan Modal Upload, serta pratinjau (preview) gambar yang
   bisa diklik tanpa harus pindah halaman (menggunakan Javascript).
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('title') ?>
Galeri Dokumentasi Wisata
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Galeri</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Professional Small Boxes -->
<div class="row mb-4">
    <div class="col-lg-4 col-12">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3><?= $totalDestinasi ?></h3>
                <p>Destinasi Wisata</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"></path>
            </svg>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3><?= $totalFoto ?></h3>
                <p>Total Foto</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"></path>
            </svg>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3><?= $totalVideo ?></h3>
                <p>Total Video</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- ANALYTICS CHARTS ROW -->
<div class="row mb-4">
    <div class="col-lg-5">
        <div class="card shadow h-100">
            <div class="card-header border-0">
                <h3 class="card-title">Proporsi Media per Kategori</h3>
            </div>
            <div class="card-body">
                <div id="chart-media-kategori"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card shadow h-100">
            <div class="card-header border-0">
                <h3 class="card-title">Top 5 Destinasi (Koleksi Foto Terbanyak)</h3>
            </div>
            <div class="card-body">
                <div id="chart-top-destinasi"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" id="searchGaleri" class="form-control" placeholder="Cari nama destinasi wisata...">
        </div>
    </div>
</div>

<?php foreach ($groupedDestinasi as $kat_id => $group): ?>
    <div class="kategori-group">
        <h4 class="mt-4 mb-3 fw-bold text-secondary border-bottom pb-2 kategori-title">
            <i class="bi bi-tag-fill me-2 text-primary"></i> <?= esc($group['nama_kategori']) ?>
        </h4>
        <div class="row destinasi-row">
            <?php foreach ($group['destinasi'] as $destinasi): ?>
                <div class="col-md-6 col-lg-4 mb-4 destinasi-card" data-nama="<?= strtolower(esc($destinasi['nama_wisata'])) ?>">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light d-flex align-items-center">
                            <h5 class="card-title m-0 fw-bold fs-6 text-truncate" style="max-width: 70%;" title="<?= esc($destinasi['nama_wisata']) ?>">
                                <?= esc($destinasi['nama_wisata']) ?>
                            </h5>
                            <div class="ms-auto">
                                <button class="btn btn-primary btn-sm btn-upload" data-id="<?= $destinasi['id'] ?>" data-nama="<?= esc($destinasi['nama_wisata']) ?>">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                            </div>
                        </div>
                        <div class="card-body bg-body-tertiary">
                            <?php if (empty($destinasi['galeri'])): ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-camera fs-1 opacity-50"></i>
                                    <p class="mt-2 mb-0 fs-7">Belum ada foto/video</p>
                                </div>
                            <?php else: ?>
                                <div class="row g-2">
                                    <?php foreach ($destinasi['galeri'] as $media): ?>
                                        <div class="col-4 position-relative group-media">
                                            <?php
                                            $mediaUrl = base_url('uploads/galeri/' . $media['nama_file']);
                                            $isFoto = ($media['tipe_file'] === 'foto');
                                            ?>
                                            <div class="cursor-pointer" onclick="openPreviewModal('<?= $mediaUrl ?>', '<?= $media['tipe_file'] ?>')" title="Klik untuk lihat">
                                                <?php if ($isFoto): ?>
                                                    <img src="<?= $mediaUrl ?>" class="img-fluid rounded object-fit-cover w-100 skeleton-effect" style="height: 80px;" alt="Galeri" loading="lazy" onload="this.classList.remove('skeleton-effect')">
                                                <?php else: ?>
                                                    <div class="bg-dark rounded d-flex align-items-center justify-content-center w-100 position-relative skeleton-effect" style="height: 80px; overflow: hidden;">
                                                        <video src="<?= $mediaUrl ?>" class="position-absolute w-100 h-100 object-fit-cover opacity-50" muted preload="metadata" onloadedmetadata="this.parentElement.classList.remove('skeleton-effect');"></video>
                                                        <i class="bi bi-play-circle text-white fs-3 position-relative z-1"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="position-absolute top-0 end-0 p-1 z-2">
                                                <button type="button" class="btn btn-danger btn-sm p-0 d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; opacity: 0.8;" onclick="confirmDelete('<?= base_url('admin/galeri/delete/' . $media['id']) ?>')" title="Hapus">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer text-muted fs-7 text-center">
                            Total Media: <strong><?= count($destinasi['galeri']) ?></strong> item
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

<?php if (empty($groupedDestinasi)): ?>
    <div class="alert alert-warning">Belum ada destinasi wisata yang tersedia.</div>
<?php endif; ?>

<!-- ======================================================
     MODAL UPLOAD GALERI (CREATE)
     
     Ini adalah formulir popup khusus untuk mengunggah file media.
     Saat tombol "Upload" diklik, kotak dialog ini akan muncul.
     Bagian `<input type="file" multiple>` memungkinkan admin untuk
     memilih banyak file sekaligus (namun dibatasi logika Controller 
     maksimal 5 foto/video per tempat wisata).
     
     Kolom Isian (Input) yang ada di form ini:
     - Destinasi Wisata   (Wajib, Dropdown dari tabel Destinasi)
     - File Media         (Wajib, bisa pilih >1 file gambar/video)
====================================================== -->
<!-- Modal Upload Galeri -->
<div class="modal fade" id="modalUpload" tabindex="-1" aria-labelledby="modalUploadLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/galeri/store') ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="destinasi_id" id="upload_destinasi_id">
                <div class="modal-header text-bg-primary">
                    <h5 class="modal-title" id="modalUploadLabel">Upload Dokumentasi Wisata</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 fs-7">
                        <i class="bi bi-info-circle me-1"></i> Mengunggah file untuk destinasi: <strong id="upload_destinasi_nama"></strong>
                    </div>
                    <div class="mb-3">
                        <label for="media" class="form-label">Pilih Foto / Video <span class="text-danger">*</span></label>
                        <input class="form-control" type="file" id="media" name="media[]" accept="image/*,video/*" multiple required>
                        <div class="form-text">Anda dapat memilih lebih dari satu file sekaligus (Total maksimal 5 file per destinasi). Ekstensi diizinkan: JPG, PNG (Max 3MB), MP4 (Max 20MB).</div>
                        <div class="invalid-feedback">Silakan pilih file media.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnBatal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnUploadSubmit"><i class="bi bi-cloud-arrow-up me-1"></i> Mulai Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Preview Media -->
<div class="modal fade" id="modalPreview" tabindex="-1" aria-labelledby="modalPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0 text-end d-flex justify-content-end w-100 position-absolute top-0 end-0 z-3 p-3">
                <button type="button" class="btn btn-dark btn-sm rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 36px; height: 36px;" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x fs-4"></i>
                </button>
            </div>
            <div class="modal-body p-0 text-center d-flex justify-content-center align-items-center" id="previewContainer" style="min-height: 200px;">
                <!-- Content injected via JS -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form Validation & Upload Spinner
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                    form.classList.add('was-validated')
                } else {
                    // If valid, show loading spinner
                    const btnSubmit = document.getElementById('btnUploadSubmit');
                    const btnBatal = document.getElementById('btnBatal');
                    if (btnSubmit) {
                        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengunggah...';
                        btnSubmit.disabled = true;
                        if (btnBatal) btnBatal.disabled = true;
                    }
                }
            }, false)
        });

        document.querySelectorAll('.btn-upload').forEach(btn => {
            btn.addEventListener('click', function() {
                // Ambil data dari atribut HTML
                document.getElementById('upload_destinasi_id').value = this.getAttribute('data-id');
                document.getElementById('upload_destinasi_nama').innerText = this.getAttribute('data-nama');

                // Gunakan getOrCreateInstance agar aman dan tidak membuat elemen ganda di background
                var modalUpload = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalUpload'));
                modalUpload.show();
            });
        });


        // Preview Modal Logic
        var modalPreviewEl = document.getElementById('modalPreview');
        var previewModal = new bootstrap.Modal(modalPreviewEl);

        window.openPreviewModal = function(url, type) {
            const container = document.getElementById('previewContainer');
            container.innerHTML = '<div class="spinner-border text-light" role="status"></div>';
            previewModal.show();

            setTimeout(() => {
                if (type === 'foto') {
                    container.innerHTML = `<img src="${url}" class="img-fluid rounded shadow-lg" style="max-height: 85vh; width: auto;" alt="Preview Foto">`;
                } else {
                    container.innerHTML = `<video src="${url}" class="w-100 rounded shadow-lg" style="max-height: 85vh;" controls autoplay controlsList="nodownload"></video>`;
                }
            }, 300); // slight delay for smooth transition
        };

        // Stop video when modal closes
        modalPreviewEl.addEventListener('hidden.bs.modal', function() {
            document.getElementById('previewContainer').innerHTML = '';
        });

        // Filter by name
        const searchInput = document.getElementById('searchGaleri');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();
                const groups = document.querySelectorAll('.kategori-group');

                groups.forEach(group => {
                    const cards = group.querySelectorAll('.destinasi-card');
                    let visibleCount = 0;

                    cards.forEach(card => {
                        if (card.dataset.nama.includes(keyword)) {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    if (visibleCount > 0) {
                        group.style.display = 'block';
                    } else {
                        group.style.display = 'none';
                    }
                });
            });
        }
        // ================= ANALYTICS CHARTS =================
        const dataGrouped = <?= json_encode($groupedDestinasi) ?>;

        const mediaKategori = {};
        const destMediaCount = [];

        Object.values(dataGrouped).forEach(group => {
            let catTotal = 0;
            group.destinasi.forEach(d => {
                let galeriCount = d.galeri ? d.galeri.length : 0;
                catTotal += galeriCount;
                destMediaCount.push({
                    nama: d.nama_wisata,
                    count: galeriCount
                });
            });
            mediaKategori[group.nama_kategori] = catTotal;
        });

        // 1. Kategori Donut
        if (document.querySelector("#chart-media-kategori")) {
            new ApexCharts(document.querySelector("#chart-media-kategori"), {
                series: Object.values(mediaKategori),
                labels: Object.keys(mediaKategori),
                chart: {
                    type: 'donut',
                    height: 250
                },
                theme: {
                    palette: 'palette3'
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

        // 2. Top Destinasi Horizontal Bar
        destMediaCount.sort((a, b) => b.count - a.count);
        const top5 = destMediaCount.slice(0, 5);
        if (document.querySelector("#chart-top-destinasi")) {
            new ApexCharts(document.querySelector("#chart-top-destinasi"), {
                series: [{
                    name: 'Total Media',
                    data: top5.map(x => x.count)
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
                colors: ['#6f42c1'],
                dataLabels: {
                    enabled: true,
                    offsetX: 20,
                    style: {
                        colors: ['#333']
                    }
                },
                xaxis: {
                    categories: top5.map(x => x.nama)
                }
            }).render();
        }
    });
</script>
<style>
    .group-media {
        cursor: pointer;
    }

    .group-media:hover .cursor-pointer {
        opacity: 0.8;
        transition: 0.3s;
    }

    /* Enhance the play button visibility on hover */
    .group-media:hover .bi-play-circle {
        transform: scale(1.2);
        transition: transform 0.3s ease;
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
<?= $this->endSection() ?>