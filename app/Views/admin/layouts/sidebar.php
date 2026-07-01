<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="<?= base_url('admin/dashboard') ?>" class="brand-link">
      <!--begin::Brand Image-->
      <img src="<?= base_url('logo.svg') ?>" alt="SiWisata Balam Logo" class="brand-image opacity-75 shadow bg-white rounded-circle p-1" />
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <span class="brand-text fw-light">SiWisata Balam</span>
      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!--end::Sidebar Brand-->
  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <!--begin::Sidebar Menu-->
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="navigation"
        data-accordion="false">
        <li class="nav-item">
          <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header">MASTER DATA</li>
        <li class="nav-item">
          <a href="<?= base_url('admin/master-data') ?>" class="nav-link <?= strpos(uri_string(), 'admin/master-data') !== false ? 'active' : '' ?>">
            <i class="nav-icon bi bi-tags"></i>
            <p>Kategori & Fasilitas</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('admin/destinasi') ?>" class="nav-link <?= strpos(uri_string(), 'admin/destinasi') !== false ? 'active' : '' ?>">
            <i class="nav-icon bi bi-geo-alt"></i>
            <p>Destinasi Wisata</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('admin/galeri') ?>" class="nav-link <?= strpos(uri_string(), 'admin/galeri') !== false ? 'active' : '' ?>">
            <i class="nav-icon bi bi-images"></i>
            <p>Galeri Wisata</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('admin/webgis') ?>" class="nav-link <?= strpos(uri_string(), 'admin/webgis') !== false ? 'active' : '' ?>">
            <i class="nav-icon bi bi-map"></i>
            <p>WebGIS Peta</p>
          </a>
        </li>

        <li class="nav-header">PENGATURAN</li>
        <li class="nav-item">
          <a href="<?= base_url('admin/users') ?>" class="nav-link <?= strpos(uri_string(), 'admin/users') !== false ? 'active' : '' ?>">
            <i class="nav-icon bi bi-people"></i>
            <p>Manajemen Admin</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('admin/profil') ?>" class="nav-link <?= strpos(uri_string(), 'admin/profil') !== false ? 'active' : '' ?>">
            <i class="nav-icon bi bi-person-circle"></i>
            <p>Profil Admin</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('admin/activity-log') ?>" class="nav-link <?= strpos(uri_string(), 'admin/activity-log') !== false ? 'active' : '' ?>">
            <i class="nav-icon bi bi-list-check"></i>
            <p>Activity Log</p>
          </a>
        </li>

        <li class="nav-header">SISTEM</li>
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->