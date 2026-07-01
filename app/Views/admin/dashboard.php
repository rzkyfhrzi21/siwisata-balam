<?php
/* ======================================================
   VIEW DASHBOARD (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna. 
   
   Tampilan utama setelah admin berhasil masuk.
   Berfungsi layaknya papan pengumuman besar yang menampilkan 
   seluruh metrik penting (Angka, Grafik, Aktivitas Terbaru)
   agar pimpinan/admin bisa langsung tahu kondisi data wisata saat ini.
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Dashboard</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible shadow-sm">
                    <h5><i class="icon bi bi-info-circle"></i> Selamat Datang!</h5>
                    Selamat datang kembali, <strong><?= session()->get('nama') ?? 'Admin' ?></strong> di Sistem Informasi Destinasi Wisata Kota Bandar Lampung.
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-primary">
                    <div class="inner">
                        <h3><?= isset($total_destinasi) ? $total_destinasi : 0 ?></h3>
                        <p>Total Destinasi</p>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"></path>
                    </svg>
                    <a href="<?= base_url('admin/destinasi') ?>" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                        Kelola Destinasi <i class="bi bi-link-45deg"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                    <div class="inner">
                        <h3>Rp <?= number_format($avg_ticket_price ?? 0, 0, ',', '.') ?></h3>
                        <p>Rata-rata Harga Tiket</p>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"></path>
                        <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v14.25c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 19.125V4.875zm11.25 1.125a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5zm0 10.5a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5zM4.5 6a.75.75 0 00-.75.75v10.5c0 .414.336.75.75.75h2.25a.75.75 0 00.75-.75V6.75a.75.75 0 00-.75-.75H4.5z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                        Analisis Nilai Aset <i class="bi bi-bar-chart"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-warning">
                    <div class="inner">
                        <h3><?= isset($total_galeri) ? $total_galeri : 0 ?></h3>
                        <p>Total Aset Galeri (Foto)</p>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"></path>
                    </svg>
                    <a href="<?= base_url('admin/galeri') ?>" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                        Lihat Penyimpanan <i class="bi bi-link-45deg"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-info">
                    <div class="inner">
                        <h3><?= isset($geo_completeness) ? $geo_completeness : 0 ?><sup style="font-size: 20px">%</sup></h3>
                        <p>Kelengkapan Koordinat</p>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="<?= base_url('admin/webgis') ?>" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                        Peta WebGIS <i class="bi bi-geo-alt-fill"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- ======================================================
             BARIS 1 GRAFIK (CHARTS)
             
             Bagian ini menampilkan dua buah Card (kotak kerangka) untuk grafik:
             1. Portofolio Kategori (berbentuk Donut Chart / Lingkaran)
             2. Fasilitas Paling Populer (berbentuk Horizontal Bar / Batang Mendatar)
             Gambar grafiknya dirender secara otomatis oleh JavaScript di bawah file.
        ====================================================== -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4 shadow">
                    <div class="card-header border-0">
                        <h3 class="card-title">Portofolio Kategori</h3>
                    </div>
                    <div class="card-body">
                        <!-- Tempat Donut Chart akan digambar oleh JavaScript -->
                        <?php if(!empty($chart_series)): ?>
                            <div id="kategori-chart"></div>
                        <?php else: ?>
                            <div class="alert alert-light text-center border">Belum ada data destinasi</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card mb-4 shadow">
                    <div class="card-header border-0">
                        <h3 class="card-title">Fasilitas Paling Populer (Top 7)</h3>
                    </div>
                    <div class="card-body">
                        <!-- Tempat Horizontal Bar Chart akan digambar oleh JavaScript -->
                        <?php if(!empty($top_fas_series)): ?>
                            <div id="fasilitas-chart"></div>
                        <?php else: ?>
                            <div class="alert alert-light text-center border">Belum ada data fasilitas.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================
             BARIS 2 GRAFIK & AKTIVITAS (CHARTS & ACTIVITY)
             
             Bagian ini menampilkan dua buah Card:
             1. Grafik Segmentasi Harga Tiket (Vertical Bar)
             2. Tabel Daftar Aktivitas Terbaru (Log aktivitas admin)
        ====================================================== -->
        <div class="row">
            <div class="col-lg-5">
                <div class="card mb-4 shadow">
                    <div class="card-header border-0">
                        <h3 class="card-title">Segmentasi Harga Tiket</h3>
                    </div>
                    <div class="card-body">
                        <!-- Tempat Vertical Bar Chart akan digambar oleh JavaScript -->
                        <div id="price-chart"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card mb-4 shadow">
                    <div class="card-header border-0">
                        <h3 class="card-title">Aktivitas Terbaru</h3>
                        <div class="card-tools">
                            <a href="<?= base_url('admin/activity-log') ?>" class="btn btn-tool btn-sm">
                                <i class="bi bi-list"></i> Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Aktor</th>
                                    <th>Aktivitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($logs) && count($logs) > 0): ?>
                                    <?php foreach($logs as $log): ?>
                                    <tr>
                                        <td><small><?= date('d M Y, H:i', strtotime($log->created_at)) ?></small></td>
                                        <td><span class="badge text-bg-primary"><?= esc($log->name) ?></span></td>
                                        <td><?= esc($log->activity) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada aktivitas.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if(!empty($chart_series)): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Kategori Donut Chart
        if (document.querySelector("#kategori-chart")) {
            var catOptions = {
                series: <?= json_encode($chart_series) ?>,
                labels: <?= json_encode($chart_labels) ?>,
                chart: { type: 'donut', height: 280 },
                theme: { palette: 'palette1' },
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: { donut: { size: '70%' } }
                },
                legend: { position: 'bottom' }
            };
            new ApexCharts(document.querySelector("#kategori-chart"), catOptions).render();
        }

        // 2. Top Fasilitas Horizontal Bar
        if (document.querySelector("#fasilitas-chart")) {
            var fasOptions = {
                series: [{ name: 'Total Wisata', data: <?= json_encode($top_fas_series) ?> }],
                chart: { type: 'bar', height: 280, toolbar: { show: false } },
                plotOptions: {
                    bar: { horizontal: true, borderRadius: 4, dataLabels: { position: 'top' } }
                },
                colors: ['#0d6efd'],
                dataLabels: { enabled: true, offsetX: 20, style: { colors: ['#333'] } },
                xaxis: { categories: <?= json_encode($top_fas_labels) ?> }
            };
            new ApexCharts(document.querySelector("#fasilitas-chart"), fasOptions).render();
        }

        // 3. Price Segment Vertical Bar
        if (document.querySelector("#price-chart")) {
            var priceOptions = {
                series: [{ name: 'Destinasi', data: <?= json_encode($price_series) ?> }],
                chart: { type: 'bar', height: 280, toolbar: { show: false } },
                plotOptions: { bar: { borderRadius: 4, distributed: true, columnWidth: '50%' } },
                dataLabels: { enabled: false },
                colors: ['#198754', '#0dcaf0', '#ffc107', '#dc3545'],
                xaxis: { categories: <?= json_encode($price_labels) ?> },
                legend: { show: false }
            };
            new ApexCharts(document.querySelector("#price-chart"), priceOptions).render();
        }
    });
</script>
<?php endif; ?>
<?= $this->endSection() ?>
