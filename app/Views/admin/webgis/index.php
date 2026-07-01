<?php
/* ======================================================
   VIEW WEBGIS (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna. 
   
   Ini adalah peta raksasa. Menampilkan seluruh tempat wisata
   dalam satu peta utuh yang bisa digeser dan di-zoom (menggunakan
   pustaka LeafletJS). Di atas peta juga ada kotak pencarian untuk
   mencari wisata berdasarkan nama.
====================================================== */
?>
<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('title') ?>
Peta Interaktif (WebGIS)
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">WebGIS</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- ======================================================
     BAGIAN HEADER (HEADER SECTION)
     
     Bagian atas ini menampilkan Judul Halaman dan juga kontrol peta
     (Tombol "Lokasi Saya", Label Total Titik, dan Dropdown Filter).
====================================================== -->
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="h4 mb-1 fw-bold text-uppercase tracking-wider">WebGIS Pariwisata</h2>
        <p class="text-secondary small mb-0">Pemetaan titik lokasi destinasi wisata Kota Bandar Lampung</p>
    </div>
    <div class="col-md-6">
        <div class="d-flex flex-column flex-md-row justify-content-md-end align-items-md-center gap-3 mt-3 mt-md-0">
            <button id="btn-locate-me" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm d-flex align-items-center" title="Lokasi Saya">
                <i class="bi bi-crosshair me-1"></i> Lokasi Saya
            </button>
            <span class="badge text-bg-primary fs-7 px-3 py-2 shadow-sm rounded-pill d-flex align-items-center">
                <i class="bi bi-geo-alt-fill me-2"></i> Total: <?= count($points) ?> Titik
            </span>
            <form action="" method="GET" class="d-flex align-items-center gap-2 m-0 flex-nowrap justify-content-end">
                <select name="kategori" id="kategori" onchange="this.form.submit()" class="form-select form-select-sm shadow-sm rounded-pill" style="min-width: 140px;">
                    <option value="">Semua Kategori</option>
                    <?php foreach($kategoriList as $kat): ?>
                        <option value="<?= $kat['slug'] ?>" <?= ($kategori_slug == $kat['slug']) ? 'selected' : '' ?>>
                            <?= esc($kat['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="fasilitas" id="fasilitas" onchange="this.form.submit()" class="form-select form-select-sm shadow-sm rounded-pill" style="min-width: 140px;">
                    <option value="">Semua Fasilitas</option>
                    <?php foreach($fasilitasList as $fas): ?>
                        <option value="<?= $fas['slug'] ?>" <?= ($fasilitas_slug == $fas['slug']) ? 'selected' : '' ?>>
                            <?= esc($fas['nama_fasilitas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================
     KOTAK PETA UTAMA (MAP CONTAINER)
     
     Ini adalah bingkai besar tempat peta akan ditampilkan.
====================================================== -->
<div class="card shadow-lg border-0 rounded-4 overflow-hidden position-relative mb-4">
    <!-- ======================================================
         KOTAK PENCARIAN MENGAMBANG (FLOATING SEARCH BOX)
         
         Ini adalah kotak input pencarian yang melayang di atas peta 
         (pojok kanan atas) untuk mencari nama destinasi dengan cepat.
    ====================================================== -->
    <div class="position-absolute top-0 end-0 mt-3 me-3 z-3" style="width: 320px;">
        <div class="position-relative">
            <input type="text" id="search-destinasi" class="form-control form-control-lg rounded-pill ps-4 pe-5 shadow border-0 bg-white" placeholder="Cari nama destinasi..." style="font-size: 0.95rem;">
            <div class="position-absolute top-50 end-0 translate-middle-y pe-4 text-secondary">
                <i class="bi bi-search"></i>
            </div>
        </div>
        <!-- Tempat Hasil Pencarian akan dimunculkan oleh JavaScript -->
        <ul id="search-destinasi-results" class="list-group position-absolute w-100 mt-2 shadow-lg rounded-3 d-none overflow-auto" style="max-height: 300px; z-index: 1001;">
        </ul>
    </div>

    <!-- ======================================================
         KANVAS PETA 
         Elemen <div id="mapViewer"> inilah yang akan "digambar" 
         oleh Javascript (LeafletJS) menjadi sebuah peta interaktif.
    ====================================================== -->
    <div id="mapViewer" style="height: calc(100vh - 250px); min-height: 400px; z-index: 1;"></div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if points data exists
    const rawPoints = <?= json_encode($points) ?>;
    
    // Initialize Map centered at Bandar Lampung
    const map = L.map('mapViewer', {
        zoomControl: false // move zoom control to bottom right
    }).setView([-5.4254, 105.258], 12);
    
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Add Tile Layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Store markers for searching
    const markersData = [];
    const markerGroup = L.featureGroup().addTo(map);

    // Custom Icon for Destinations
    const pinIcon = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Render Markers
    rawPoints.forEach(point => {
        if (point.latitude && point.longitude) {
            const marker = L.marker([parseFloat(point.latitude), parseFloat(point.longitude)], { icon: pinIcon });
            
            marker.bindPopup(point.popupHtml, {
                maxWidth: 300,
                minWidth: 280,
                className: 'custom-popup-card'
            });
            
            markerGroup.addLayer(marker);
            
            markersData.push({
                id: point.id,
                name: point.name,
                category: point.category,
                marker: marker,
                lat: parseFloat(point.latitude),
                lng: parseFloat(point.longitude)
            });
        }
    });

    // Auto-fit map bounds to markers if any exist
    if (markersData.length > 0) {
        map.fitBounds(markerGroup.getBounds(), { padding: [50, 50] });
    }

    // Reverse Geocoding with OSM Nominatim when popup opens
    map.on('popupopen', function(e) {
        const marker = e.popup._source;
        // Check if this is a destination marker
        const destMarker = markersData.find(m => m.marker === marker);
        if (destMarker) {
            const alamatEl = document.getElementById('alamat-' + destMarker.id);
            if (alamatEl && alamatEl.innerHTML.includes('Memuat...')) {
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${destMarker.lat}&lon=${destMarker.lng}&zoom=18&addressdetails=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            alamatEl.innerHTML = `: ${data.display_name}`;
                        } else {
                            alamatEl.innerHTML = ': Alamat tidak ditemukan';
                        }
                    })
                    .catch(error => {
                        alamatEl.innerHTML = ': Gagal memuat alamat';
                    });
            }
        } else if (marker === userMarker) {
            // It's the user marker
            const userAlamatEl = document.getElementById('user-alamat');
            if (userAlamatEl && userAlamatEl.innerHTML.includes('Memuat...')) {
                const latlng = marker.getLatLng();
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}&zoom=18&addressdetails=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            userAlamatEl.innerHTML = data.display_name;
                        } else {
                            userAlamatEl.innerHTML = 'Alamat tidak ditemukan';
                        }
                    })
                    .catch(error => {
                        userAlamatEl.innerHTML = 'Gagal memuat alamat';
                    });
            }
        }
    });

    // Floating Search Logic
    const searchInput = document.getElementById('search-destinasi');
    const resultsDropdown = document.getElementById('search-destinasi-results');
    
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        resultsDropdown.innerHTML = '';
        
        if (query.length < 1) {
            resultsDropdown.classList.add('d-none');
            return;
        }

        const filtered = markersData.filter(item => 
            item.name.toLowerCase().includes(query) || 
            item.category.toLowerCase().includes(query)
        );
        
        if (filtered.length > 0) {
            filtered.slice(0, 10).forEach(item => {
                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-action cursor-pointer';
                li.style.cursor = 'pointer';
                li.innerHTML = `<div class="fw-bold text-dark">${item.name}</div><div class="fs-7 text-secondary"><i class="bi bi-tag-fill me-1"></i> ${item.category}</div>`;
                
                li.addEventListener('click', () => {
                    map.setView([item.lat, item.lng], 16);
                    item.marker.openPopup();
                    resultsDropdown.classList.add('d-none');
                    searchInput.value = item.name;
                });
                
                resultsDropdown.appendChild(li);
            });
            resultsDropdown.classList.remove('d-none');
        } else {
            const li = document.createElement('li');
            li.className = 'list-group-item text-muted text-center fst-italic';
            li.textContent = 'Destinasi tidak ditemukan';
            resultsDropdown.appendChild(li);
            resultsDropdown.classList.remove('d-none');
        }
    });

    // Close dropdown on click outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsDropdown.contains(e.target)) {
            resultsDropdown.classList.add('d-none');
        }
    });

    // Locate Me Logic
    const locateBtn = document.getElementById('btn-locate-me');
    let userMarker = null;
    let userCircle = null;

    locateBtn.addEventListener('click', function() {
        locateBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...';
        map.locate({setView: true, maxZoom: 16});
    });

    map.on('locationfound', function(e) {
        locateBtn.innerHTML = '<i class="bi bi-crosshair me-1"></i> Lokasi Saya';

        const radius = e.accuracy / 2;

        if (userMarker) {
            map.removeLayer(userMarker);
            map.removeLayer(userCircle);
        }

        const userIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        userMarker = L.marker(e.latlng, { icon: userIcon }).addTo(map)
            .bindPopup(`
                <div class="text-center">
                    <b>Lokasi Anda Saat Ini</b><br>
                    <span class="fs-7 text-secondary">Lat: ${e.latlng.lat.toFixed(5)}, Lng: ${e.latlng.lng.toFixed(5)}</span>
                    <hr class="my-2">
                    <div id="user-alamat" class="small"><span class="spinner-border spinner-border-sm text-primary" role="status"></span> Memuat...</div>
                </div>
            `).openPopup();
        
        userCircle = L.circle(e.latlng, radius).addTo(map);
    });

    map.on('locationerror', function(e) {
        locateBtn.innerHTML = '<i class="bi bi-crosshair me-1"></i> Lokasi Saya';
        alert("Gagal mengakses lokasi. Pastikan izin lokasi diberikan pada browser Anda.");
    });
});
</script>

<style>
    /* Styling for leaflet custom popup padding override to fit standard bootstrap card seamlessly */
    .custom-popup-card .leaflet-popup-content-wrapper {
        padding: 0;
        border-radius: 8px;
        overflow: hidden;
    }
    .custom-popup-card .leaflet-popup-content {
        margin: 0;
        width: 100% !important;
    }
    .custom-popup-card .card {
        border-radius: 0;
    }
    .z-3 {
        z-index: 3 !important;
    }
</style>
<?= $this->endSection() ?>
