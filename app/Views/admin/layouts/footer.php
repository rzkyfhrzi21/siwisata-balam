<!--begin::Footer-->
<footer class="app-footer text-center bg-white border-top py-3">
  <strong>
    Copyright &copy; <?= (date('Y') == '2026') ? '2026' : '2026 - ' . date('Y') ?>&nbsp;
    <a href="#" class="text-decoration-none text-primary fw-bold"><?= esc(getenv('app.siteName') ?? 'SiWisata Balam') ?></a>.
  </strong>
  <span class="text-secondary d-none d-md-inline ms-1">
    Made with <i class="bi bi-heart-fill text-danger"></i> by <?= esc(getenv('app.author') ?? 'Developer') ?>. All rights reserved.
  </span>
</footer>
<!--end::Footer-->