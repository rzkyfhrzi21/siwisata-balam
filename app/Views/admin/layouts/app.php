<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?= $this->renderSection('title') ?> | <?= esc(getenv('app.siteName')) ?></title>
    
    <link rel="icon" href="<?= base_url('logo.svg') ?>" type="image/svg+xml">

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="SiWisata Balam | Admin Dashboard" />
    <meta name="author" content="SiWisata Balam" />
    <meta name="robots" content="noindex, nofollow" />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="<?= base_url('assets/adminlte/css/') ?>adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media = 'all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/') ?>adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- Tabulator CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">

    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    <!-- Custom Premium Toasts CSS -->
    <style>
      .toast-container {
        padding: 1.5rem !important;
      }
      .toast {
        --toast-duration: 5000ms;
        background: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(12px) saturate(180%);
        -webkit-backdrop-filter: blur(12px) saturate(180%);
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        border-radius: 12px !important;
        box-shadow: 0 15px 35px rgba(13, 110, 253, 0.10), 0 2px 6px rgba(0, 0, 0, 0.06) !important;
        overflow: hidden;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        animation: toast-entrance 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
      }
      [data-bs-theme="dark"] .toast {
        background: rgba(30, 30, 30, 0.92) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35), 0 2px 6px rgba(0, 0, 0, 0.2) !important;
      }
      @keyframes toast-entrance {
        from {
          opacity: 0;
          transform: translateX(40px) scale(0.95);
        }
        to {
          opacity: 1;
          transform: translateX(0) scale(1);
        }
      }
      /* Warna mengikuti palet tema admin (Bootstrap variables) */
      .toast.toast-success {
        border-left: 6px solid var(--bs-success, #198754) !important;
      }
      .toast.toast-danger {
        border-left: 6px solid var(--bs-danger, #dc3545) !important;
      }
      .toast.toast-warning {
        border-left: 6px solid var(--bs-warning, #ffc107) !important;
      }
      .toast.toast-info {
        border-left: 6px solid var(--bs-info, #0dcaf0) !important;
      }
      .toast-header {
        background: transparent !important;
        border-bottom: none !important;
        padding: 12px 16px 4px 16px !important;
        display: flex;
        align-items: center;
      }
      .toast-header strong {
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        letter-spacing: -0.01em;
      }
      /* Badge ikon bulat ala AdminLTE */
      .toast-icon-badge {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
        margin-right: 10px;
      }
      .toast-success .toast-icon-badge {
        background: var(--bs-success-bg-subtle, #d1e7dd);
        color: var(--bs-success, #198754);
      }
      .toast-danger .toast-icon-badge {
        background: var(--bs-danger-bg-subtle, #f8d7da);
        color: var(--bs-danger, #dc3545);
      }
      .toast-warning .toast-icon-badge {
        background: var(--bs-warning-bg-subtle, #fff3cd);
        color: #664d03;
      }
      .toast-info .toast-icon-badge {
        background: var(--bs-info-bg-subtle, #cff4fc);
        color: #055160;
      }
      .toast-success .toast-title { color: var(--bs-success, #198754) !important; }
      .toast-danger  .toast-title { color: var(--bs-danger, #dc3545) !important; }
      .toast-warning .toast-title { color: #997404 !important; }
      .toast-info    .toast-title { color: #055160 !important; }
      .toast-time {
        font-size: 0.75rem;
        color: #6c757d;
        white-space: nowrap;
      }
      [data-bs-theme="dark"] .toast-time,
      [data-bs-theme="dark"] .toast-body {
        color: #adb5bd !important;
      }
      .toast-body {
        padding: 0 16px 14px 60px !important;
        font-size: 0.875rem !important;
        color: #495057 !important;
        line-height: 1.55;
      }
      .toast .btn-close {
        margin-left: auto !important;
        background-size: 0.7rem;
        opacity: 0.45;
        transition: opacity 0.2s ease;
      }
      .toast .btn-close:hover {
        opacity: 0.9;
      }
      /* Progress bar hitung mundur sebelum toast tersembunyi otomatis */
      .toast-progress {
        position: absolute;
        left: 0;
        bottom: 0;
        height: 4px;
        width: 100%;
        transform-origin: left;
        animation: toast-progress-shrink var(--toast-duration) linear forwards;
      }
      .toast-success .toast-progress { background: var(--bs-success, #198754); }
      .toast-warning .toast-progress { background: var(--bs-warning, #ffc107); }
      .toast-info    .toast-progress { background: var(--bs-info, #0dcaf0); }
      @keyframes toast-progress-shrink {
        from { transform: scaleX(1); }
        to   { transform: scaleX(0); }
      }
    </style>

    <!-- Dark Mode Fixes: teks chart & card agar tetap terbaca -->
    <style>
      /* Teks sumbu & label ApexCharts (SVG) mengikuti warna body saat mode gelap.
         CSS dengan !important mampu menimpa fill/inline style bawaan ApexCharts
         maupun warna hardcode (mis. #333, #304758) dari konfigurasi chart. */
      [data-bs-theme="dark"] .apexcharts-canvas text {
        fill: #ced4da !important;
        color: #ced4da !important;
      }
      [data-bs-theme="dark"] .apexcharts-legend-text {
        color: #ced4da !important;
      }
      [data-bs-theme="dark"] .apexcharts-datalabel,
      [data-bs-theme="dark"] .apexcharts-datalabels-group text {
        fill: #e9ecef !important;
        color: #e9ecef !important;
      }
      [data-bs-theme="dark"] .apexcharts-gridline {
        stroke: rgba(255, 255, 255, 0.08);
      }
      [data-bs-theme="dark"] .apexcharts-xaxis-tick,
      [data-bs-theme="dark"] .apexcharts-yaxis-tick {
        stroke: rgba(255, 255, 255, 0.15);
      }

      /* Card header ber-bg-light tetap gelap agar judul terbaca di mode gelap */
      [data-bs-theme="dark"] .card-header.bg-light {
        background-color: var(--bs-body-bg) !important;
        border-bottom-color: var(--bs-border-color) !important;
      }

      /* Tabel admin di mode mobile: jangan menciutkan kolom,
         normalkan lebar lalu geser kanan-kiri dengan scrollbar */
      @media (max-width: 767.98px) {
        .table-scroll {
          overflow-x: auto;
          -webkit-overflow-scrolling: touch;
        }
        .table-scroll .tabulator {
          min-width: 720px;
        }
        .table-scroll .table-responsive {
          overflow-x: visible; /* scroll ditangani .table-scroll */
        }
      }
    </style>
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      
        <?= $this->include('admin/layouts/header') ?>

      
        <?= $this->include('admin/layouts/sidebar') ?>

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0"><?= $this->renderSection('title') ?></h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end"><?= $this->renderSection('breadcrumb') ?></ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
            <div class="app-content">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
            <!--end::App Content-->
      </main>
      <!--end::App Main-->
      
        <?= $this->include('admin/layouts/footer') ?>

    </div>
    <!--end::App Wrapper-->
    <!--begin::Toast Container-->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1060;">
        <?php
        // Konfigurasi tampilan tiap tipe toast:
        // - success/warning/info : auto-hide 5 detik dengan progress bar hitung mundur.
        // - danger               : TIDAK auto-hide, ditutup manual via tombol silang.
        $toastTypes = [
            'success' => ['id' => 'toastSuccess', 'icon' => 'bi-check-circle-fill',       'title' => 'Berhasil'],
            'error'   => ['id' => 'toastDanger',  'icon' => 'bi-exclamation-circle-fill', 'title' => 'Gagal'],
            'warning' => ['id' => 'toastWarning', 'icon' => 'bi-exclamation-triangle-fill', 'title' => 'Peringatan'],
            'info'    => ['id' => 'toastInfo',    'icon' => 'bi-info-circle-fill',        'title' => 'Informasi'],
        ];
        $mapClass = ['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'];
        ?>
        <?php foreach ($toastTypes as $flashKey => $cfg): ?>
            <?php if ($pesan = session()->getFlashdata($flashKey)): ?>
                <?php $isDanger = $flashKey === 'error'; ?>
                <div id="<?= $cfg['id'] ?>"
                     class="toast toast-<?= $mapClass[$flashKey] ?>"
                     role="alert"
                     aria-live="assertive"
                     aria-atomic="true"
                     data-autohide="<?= $isDanger ? 'false' : 'true' ?>">
                    <div class="toast-header">
                        <span class="toast-icon-badge"><i class="bi <?= $cfg['icon'] ?>"></i></span>
                        <strong class="me-1 toast-title"><?= $cfg['title'] ?></strong>
                        <small class="toast-time me-auto"><i class="bi bi-clock me-1"></i><?= date('H:i') ?> WIB</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Tutup"></button>
                    </div>
                    <div class="toast-body"><?= $pesan ?></div>
                    <?php if (!$isDanger): ?>
                        <div class="toast-progress"></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <!--end::Toast Container-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="<?= base_url('assets/adminlte/js/') ?>adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure--><!--begin::Color Mode Toggle (#6010)-->
    <script>
      (() => {
        'use strict';

        const STORAGE_KEY = 'lte-theme';

        const getStoredTheme = () => localStorage.getItem(STORAGE_KEY);
        const setStoredTheme = (theme) => localStorage.setItem(STORAGE_KEY, theme);

        const prefersDark = () => globalThis.matchMedia('(prefers-color-scheme: dark)').matches;

        const getPreferredTheme = () => {
          const stored = getStoredTheme();
          if (stored) return stored;
          return prefersDark() ? 'dark' : 'light';
        };

        const setTheme = (theme) => {
          const resolved = theme === 'auto' ? (prefersDark() ? 'dark' : 'light') : theme;
          document.documentElement.setAttribute('data-bs-theme', resolved);
        };

        setTheme(getPreferredTheme());

        const showActiveTheme = (theme) => {
          // Highlight the active dropdown option
          document.querySelectorAll('[data-bs-theme-value]').forEach((el) => {
            el.classList.remove('active');
            el.setAttribute('aria-pressed', 'false');
            const check = el.querySelector('.bi-check-lg');
            if (check) check.classList.add('d-none');
          });
          const active = document.querySelector(`[data-bs-theme-value="${theme}"]`);
          if (active) {
            active.classList.add('active');
            active.setAttribute('aria-pressed', 'true');
            const check = active.querySelector('.bi-check-lg');
            if (check) check.classList.remove('d-none');
          }
          // Sync the topbar trigger icon
          document.querySelectorAll('[data-lte-theme-icon]').forEach((icon) => {
            icon.classList.toggle('d-none', icon.dataset.lteThemeIcon !== theme);
          });
        };

        globalThis.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
          const stored = getStoredTheme();
          if (!stored || stored === 'auto') setTheme(getPreferredTheme());
        });

        document.addEventListener('DOMContentLoaded', () => {
          showActiveTheme(getPreferredTheme());
          document.querySelectorAll('[data-bs-theme-value]').forEach((toggle) => {
            toggle.addEventListener('click', () => {
              const theme = toggle.getAttribute('data-bs-theme-value');
              setStoredTheme(theme);
              setTheme(theme);
              showActiveTheme(theme);
            });
          });
        });
      })();
    </script>
    <!--end::Color Mode Toggle-->

    <!--begin::Toast Initialization-->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toast').forEach(function (toastEl) {
          const autohide = toastEl.dataset.autohide !== 'false';

          // Sinkronkan durasi progress bar CSS dengan delay Bootstrap.
          const delay = 2000;
          if (autohide) {
            toastEl.style.setProperty('--toast-duration', delay + 'ms');
          }

          new bootstrap.Toast(toastEl, {
            delay: delay,
            autohide: autohide
          }).show();
        });
      });
    </script>
    <!--end::Toast Initialization-->

    <!-- Tabulator JS -->
    <script src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/js/tabulator.min.js"></script>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-bg-danger">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteConfirmForm" action="" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger">Hapus Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Set up global delete confirmation function
        function confirmDelete(url) {
            document.getElementById('deleteConfirmForm').action = url;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            deleteModal.show();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>

    <?= $this->renderSection('scripts') ?>
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
