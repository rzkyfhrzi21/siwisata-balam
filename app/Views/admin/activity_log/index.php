<?php
/* ======================================================
   VIEW ACTIVITY LOG (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna. Di sini, Controller mengirimkan data
   riwayat aktivitas, lalu View ini menyusunnya menjadi grafik dan tabel interaktif
   agar mudah dibaca oleh Admin.
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('title') ?>
Activity Log
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Activity Log</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Professional Small Boxes -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="small-box text-bg-secondary">
            <div class="inner">
                <h3><?= count($logs) ?></h3>
                <p>Total Aktivitas Sistem</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path>
                <path d="M13 7h-2v6l4.25 2.5 1-1.5-3.25-2V7z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- ANALYTICS CHARTS ROW -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card shadow h-100">
            <div class="card-header border-0">
                <h3 class="card-title">Tren Frekuensi Aktivitas Harian</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($logs)): ?>
                    <div id="chart-daily-activity"></div>
                <?php else: ?>
                    <div class="alert alert-light text-center border">Belum ada data log aktivitas.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h3 class="card-title m-0">Riwayat Aktivitas Sistem</h3>
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
                <input type="text" id="table-filter" class="form-control form-control-sm" placeholder="Cari aktivitas...">
            </div>
        </div>

        <div id="logs-table"></div>
    </div>
</div>
<?= $this->endSection() ?>

<!-- ======================================================
     BAGIAN JAVASCRIPT (Tabulator & ApexCharts)
     Kode di bawah ini berfungsi menyulap tabel biasa menjadi tabel super 
     (bisa di-search, bisa diekspor ke PDF/Excel) menggunakan library Tabulator,
     sekaligus menggambar grafik batang statistik menggunakan library ApexCharts.
====================================================== -->
<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableData = <?= json_encode($logs) ?>;

        const table = new Tabulator("#logs-table", {
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
                    title: "Admin",
                    field: "name",
                    headerFilter: "input",
                    width: 150
                },
                {
                    title: "Aktivitas",
                    field: "activity",
                    headerFilter: "input"
                },
                {
                    title: "Tanggal & Waktu",
                    field: "created_at",
                    width: 200
                }
            ],
        });

        document.getElementById('table-filter').addEventListener('input', (e) => {
            const value = e.target.value;
            if (value) {
                table.setFilter([
                    [{
                            field: 'name',
                            type: 'like',
                            value: value
                        },
                        {
                            field: 'activity',
                            type: 'like',
                            value: value
                        }
                    ]
                ]);
            } else {
                table.clearFilter();
            }
        });

        document.getElementById('export-csv').addEventListener('click', () => table.download('csv', 'activity_log.csv'));
        document.getElementById('export-json').addEventListener('click', () => table.download('json', 'activity_log.json'));
        document.getElementById('print-table').addEventListener('click', () => table.print(false, true));

        // ================= ANALYTICS CHARTS =================
        if (tableData.length > 0 && document.querySelector("#chart-daily-activity")) {
            const dailyCounts = {};

            tableData.forEach(log => {
                // Extact date YYYY-MM-DD
                const dateStr = log.created_at.split(' ')[0];
                dailyCounts[dateStr] = (dailyCounts[dateStr] || 0) + 1;
            });

            // Sort dates chronologically
            const sortedDates = Object.keys(dailyCounts).sort();
            const sortedData = sortedDates.map(date => dailyCounts[date]);

            var options = {
                series: [{
                    name: 'Total Aktivitas',
                    data: sortedData
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
                        columnWidth: '50%'
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    }
                },
                colors: ['#0d6efd'],
                xaxis: {
                    categories: sortedDates,
                    type: 'datetime'
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return Math.floor(val);
                        }
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd MMM yyyy'
                    }
                }
            };
            new ApexCharts(document.querySelector("#chart-daily-activity"), options).render();
        }
    });
</script>
<?= $this->endSection() ?>