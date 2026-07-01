<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Start Navbar Links-->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>

      <li class="nav-item d-none d-md-block">
        <a href="<?= base_url() ?>" target="_blank" class="nav-link">
          <i class="bi bi-globe me-1" aria-hidden="true"></i>
          Lihat Website
        </a>
      </li>
    </ul>
    <!--end::Start Navbar Links-->

    <!--begin::End Navbar Links-->
    <ul class="navbar-nav ms-auto">
      <!--begin::Navbar Search-->
      <li class="nav-item d-none">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="bi bi-search"></i>
        </a>
      </li>
      <!--end::Navbar Search-->

      <!--begin::Time and Date-->
      <li class="nav-item d-flex align-items-center me-3">
        <div class="bg-light border shadow-sm rounded-pill px-3 py-1 d-flex align-items-center text-secondary" style="font-size: 0.95rem;">
          <i class="bi bi-clock me-2"></i> <span id="realtime-clock" class="fw-medium"><?= date('d M Y, H:i:s') ?></span>
        </div>
      </li>
      <script>
        setInterval(function() {
          const now = new Date();
          const options = {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
          };
          document.getElementById('realtime-clock').innerText = now.toLocaleString('id-ID', options).replace(/\./g, ':');
        }, 1000);
      </script>
      <!--end::Time and Date-->

      <!--begin::Fullscreen Toggle-->
      <li class="nav-item">
        <a class="nav-link" href="#" id="custom-fullscreen-toggle" role="button">
          <i id="icon-maximize" class="bi bi-arrows-fullscreen"></i>
          <i id="icon-minimize" class="bi bi-fullscreen-exit d-none"></i>
        </a>
      </li>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          const toggleBtn = document.getElementById('custom-fullscreen-toggle');
          const iconMax = document.getElementById('icon-maximize');
          const iconMin = document.getElementById('icon-minimize');

          toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!document.fullscreenElement) {
              document.documentElement.requestFullscreen().catch(err => {
                console.error(`Error attempting to enable fullscreen: ${err.message}`);
              });
            } else {
              if (document.exitFullscreen) {
                document.exitFullscreen();
              }
            }
          });

          document.addEventListener('fullscreenchange', function() {
            if (document.fullscreenElement) {
              iconMax.classList.add('d-none');
              iconMin.classList.remove('d-none');
            } else {
              iconMax.classList.remove('d-none');
              iconMin.classList.add('d-none');
            }
          });
        });
      </script>
      <!--end::Fullscreen Toggle-->

      <!--begin::Theme mode-->
      <li class="nav-item dropdown">
        <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static">
          <span class="theme-icon-active"><i class="my-1 theme-icon bi bi-sun-fill"></i></span>
          <span class="d-lg-none ms-2" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text" style="--bs-dropdown-min-width: 8rem">
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="true">
              <i class="bi bi-sun-fill me-2 opacity-50 theme-icon"></i>
              Light
              <i class="bi bi-check2 ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
              <i class="bi bi-moon-stars-fill me-2 opacity-50 theme-icon"></i>
              Dark
              <i class="bi bi-check2 ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
              <i class="bi bi-circle-half me-2 opacity-50 theme-icon"></i>
              Auto
              <i class="bi bi-check2 ms-auto d-none"></i>
            </button>
          </li>
        </ul>
      </li>
      <!--end::Theme mode-->

      <!--begin::User Menu Dropdown-->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          <?php if (session()->get('foto_profil')): ?>
            <img src="<?= base_url('uploads/profil/' . session()->get('foto_profil')) ?>" class="user-image rounded-circle shadow" alt="User Image" style="object-fit: cover; width: 32px; height: 32px;" />
          <?php else: ?>
            <div class="user-image rounded-circle shadow bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
              <?= strtoupper(substr(session()->get('nama') ?? 'A', 0, 1)) ?>
            </div>
          <?php endif; ?>
          <span class="d-none d-md-inline"><?= session()->get('nama') ?? 'Admin' ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <!--begin::User Image-->
          <li class="user-header text-bg-primary">
            <?php if (session()->get('foto_profil')): ?>
              <img src="<?= base_url('uploads/profil/' . session()->get('foto_profil')) ?>" class="rounded-circle shadow" alt="User Image" style="object-fit: cover; width: 90px; height: 90px;" />
            <?php else: ?>
              <div class="rounded-circle shadow bg-light text-primary d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; font-size: 2.5rem; margin: auto;">
                <?= strtoupper(substr(session()->get('nama') ?? 'A', 0, 1)) ?>
              </div>
            <?php endif; ?>

            <?php
            $adminModel = new \App\Models\AdminModel();
            $user = $adminModel->find(session()->get('id'));
            $memberSince = ($user && !empty($user['created_at'])) ? date('M. Y', strtotime($user['created_at'])) : date('M. Y');
            ?>

            <p>
              <?= session()->get('nama') ?? 'Admin' ?> - Administrator
              <small>Member since <?= $memberSince ?></small>
            </p>
          </li>
          <!--end::User Image-->
          <!--begin::Menu Footer-->
          <li class="user-footer">
            <a href="<?= base_url('admin/profil') ?>" class="btn btn-outline-secondary">Profile</a>
            <a href="<?= base_url('admin/logout') ?>" class="btn btn-outline-danger float-end">Sign out</a>
          </li>
          <!--end::Menu Footer-->
        </ul>
      </li>
      <!--end::User Menu Dropdown-->
    </ul>
    <!--end::End Navbar Links-->
  </div>
  <!--end::Container-->
</nav>
<!--end::Header-->