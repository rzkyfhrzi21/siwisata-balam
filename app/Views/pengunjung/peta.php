<?php
/* ======================================================
   VIEW PETA (MVC - VIEW)
   
   Apa itu View?
   View adalah kodingan HTML yang bertugas membangun antarmuka (layar/UI)
   yang dilihat oleh pengguna/pengunjung web.
   
   Halaman ini menampilkan Peta Interaktif WebGIS berukuran penuh.
   Pengunjung bisa melihat seluruh sebaran destinasi wisata dan 
   mencari wisata terdekat dengan menekan tombol "Lokasi Saya".
====================================================== */
?>
<?= $this->include('pengunjung/layout/header') ?>

<style>
    /* Fix Navbar Overlap */
    .navbar {
        background-color: var(--bs-primary) !important;
        position: relative !important;
    }

    /* Google Maps like layout */
    .map-container {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 75px); /* Navbar height is around 75px */
        margin-top: 0;
    }
    
    @media (min-width: 992px) {
        .map-container {
            flex-direction: row;
        }
    }

    .map-sidebar {
        width: 100%;
        max-height: 40vh;
        overflow-y: auto;
        background-color: #fff;
        z-index: 10;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        position: relative;
    }
    
    @media (min-width: 992px) {
        .map-sidebar {
            width: 400px;
            max-height: 100%;
            flex-shrink: 0;
        }
    }

    .map-search-box {
        padding: 1.5rem 1rem 1rem 1rem;
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 11;
        border-bottom: 1px solid #eee;
    }

    .map-results {
        flex-grow: 1;
        overflow-y: auto;
    }

    .map-result-item {
        padding: 1rem;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .map-result-item:hover {
        background-color: #f8f9fa;
    }

    .map-view {
        flex-grow: 1;
        width: 100%;
        height: 60vh;
        z-index: 1;
    }
    
    @media (min-width: 992px) {
        .map-view {
            height: 100%;
        }
    }
    
    /* Hide the footer on map page to maximize space */
    .footer, .copyright, .back-to-top, .subscribe {
        display: none !important;
    }
    
    /* Adjust popup styling */
    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        overflow: hidden;
        padding: 0;
    }
    .leaflet-popup-content {
        margin: 0;
        width: 250px !important;
    }

    /* Custom popup button styling */
    .btn-popup {
        background-color: var(--bs-primary) !important;
        color: #ffffff !important;
        border: 1px solid var(--bs-primary) !important;
        transition: all 0.3s ease;
    }
    
    .btn-popup:hover {
        background-color: #ffffff !important;
        color: var(--bs-primary) !important;
    }
</style>

<!-- ======================================================
     BAGIAN PETA INTERAKTIF WEBGIS
     
     Ini adalah area utama halaman yang dibagi menjadi dua bagian besar:
     1. Sidebar (Kolom Pencarian & Daftar Wisata) di kiri
     2. Kanvas Peta (LeafletJS) di kanan
====================================================== -->
<div class="container-fluid p-0 map-container">
    <!-- ======================================================
         BAGIAN SIDEBAR KIRI (PENCARIAN)
         
         Menampilkan kotak ketikan (input) pencarian dan daftar (list)
         destinasi wisata. Daftar ini akan diisi secara dinamis
         oleh Javascript berdasarkan kata kunci yang diketik.
    ====================================================== -->
    <div class="map-sidebar">
        <div class="map-search-box">
            <h5 class="mb-3 text-primary"><i class="fa fa-map-marked-alt me-2"></i>WebGIS Destinasi</h5>
            <div class="input-group shadow-sm border border-primary rounded-pill overflow-hidden">
                <input type="text" id="searchInput" class="form-control border-0 px-4" placeholder="Cari lokasi wisata..." style="box-shadow: none;">
                <button class="btn btn-primary border-0 px-4" type="button" id="searchBtn"><i class="fa fa-search"></i></button>
            </div>
            <div class="mt-2 text-muted small px-2 d-flex justify-content-between align-items-center">
                <span><span id="resultCount"><?= count($destinasi) ?></span> destinasi ditemukan</span>
                <button type="button" id="btnLokasiSaya" class="btn btn-sm btn-outline-primary rounded-pill"><i class="fa fa-crosshairs me-1"></i>Lokasi Saya</button>
            </div>
        </div>
        <div class="map-results" id="resultList">
            <!-- Items akan dirender dengan JS -->
        </div>
    </div>

    <!-- ======================================================
         KANVAS PETA (MAP VIEW)
         
         Ini adalah wadah/bingkai HTML kosong (`<div id="peta-wisata">`)
         yang nantinya akan "digambar" menjadi peta hidup
         oleh library LeafletJS di bagian bawah file ini.
    ====================================================== -->
    <div id="peta-wisata" class="map-view"></div>
</div>
<!-- Peta Interaktif WebGIS End -->

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inisialisasi peta, pusatkan di Kota Bandar Lampung
    var map = L.map('peta-wisata', {
        zoomControl: false // Disable default zoom to reposition it
    }).setView([-5.4292, 105.2617], 12);
    
    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);

    // Basemap OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19
    }).addTo(map);

    /* ======================================================
       PERSIAPAN DATA (ARRAY MAPPING)
       
       Kita memanggil data `$destinasi` dari Controller (tabel 'destinasi')
       lalu mengubahnya menjadi format JSON agar bisa dibaca oleh Javascript.
       
       Kolom DB yang diambil:
       - $d['id']              : ID wisata (Untuk keperluan aksi klik marker)
       - $d['nama_wisata']     : Nama destinasi
       - $d['alamat']          : Lokasi fisik
       - $d['harga_tiket']     : Untuk label harga di peta
       - $d['jam_operasional'] : Jam buka/tutup
       - $d['hari_operasional']: Hari buka/tutup
       - $d['latitude']        : Titik koordinat garis lintang (Sangat krusial untuk titik marker)
       - $d['longitude']       : Titik koordinat garis bujur (Sangat krusial untuk titik marker)
       - $d['slug']            : Link bersih untuk tombol "Lihat Detail"
       - $d['thumbnail']       : Foto sampul (Diambil dari folder `uploads/thumbnail/`)
    ====================================================== */
    var destinasi = <?= json_encode(array_map(function($d) {
        return [
            'id'          => $d['id'],
            'nama'        => $d['nama_wisata'],
            'alamat'      => $d['alamat'],
            'tiket'       => $d['harga_tiket'],
            'jam'         => $d['jam_operasional'] ?? '-',
            'hari'        => $d['hari_operasional'] ?? '-',
            'lat'         => (float)$d['latitude'],
            'lon'         => (float)$d['longitude'],
            'slug'        => $d['slug'],
            'link_gmaps'  => $d['link_gmaps'] ?? '',
            'foto'        => $d['thumbnail'] ? base_url('uploads/thumbnail/' . $d['thumbnail']) : base_url('assets/img/packages-1.jpg'),
        ];
    }, $destinasi)) ?>;

    var markers = {};

    // Custom Icon
    var defaultIcon = L.icon({
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Loop semua destinasi, buat marker
    destinasi.forEach(function(d) {
        if (d.lat && d.lon) {
            var googleMapsUrl = d.link_gmaps ? d.link_gmaps : 'https://www.google.com/maps/dir/?api=1&destination=' + d.lat + ',' + d.lon;
            
            var popup = '<div>'
                + '<img src="' + d.foto + '" class="img-fluid w-100" style="height: 120px; object-fit: cover;" onerror="this.src=\'<?= base_url('assets/img/packages-1.jpg') ?>\'">'
                + '<div class="p-3">'
                + '<h6 class="mb-1 text-primary"><b>' + d.nama + '</b></h6>'
                + '<p class="mb-1 text-muted small"><i class="fa fa-map-marker-alt me-1"></i>' + (d.alamat || '-') + '</p>'
                + '<p class="mb-1 text-muted small"><i class="fa fa-calendar-alt me-1"></i>' + d.hari + '</p>'
                + '<p class="mb-1 text-muted small"><i class="fa fa-clock me-1"></i>' + d.jam + '</p>'
                + '<p class="mb-2 text-muted small"><i class="fa fa-ticket-alt me-1"></i>' + (d.harga_tiket || 'Gratis') + '</p>'
                + '<div class="d-flex flex-column gap-2 mt-2">'
                + '<a href="<?= base_url('wisata') ?>/' + d.slug + '" class="btn btn-popup btn-sm rounded-pill px-3 w-100"><i class="fa fa-info-circle me-1"></i>Lihat Detail</a>'
                + '<a href="' + googleMapsUrl + '" target="_blank" class="btn btn-popup btn-sm rounded-pill px-3 w-100"><i class="fa fa-directions me-1"></i>Google Maps</a>'
                + '</div>'
                + '</div></div>';

            var m = L.marker([d.lat, d.lon], {icon: defaultIcon})
                .addTo(map)
                .bindPopup(popup);
                
            markers[d.id] = m;
        }
    });

    // Render List
    function renderList(data) {
        var html = '';
        if (data.length === 0) {
            html = '<div class="p-4 text-center text-muted"><i class="fa fa-search fa-2x mb-3"></i><br>Tidak ada destinasi ditemukan.</div>';
        } else {
            data.forEach(function(d) {
                var jarakHtml = '';
                if (d.jarak !== undefined) {
                    var jarakText = (d.jarak < 1) ? Math.round(d.jarak * 1000) + ' m' : d.jarak.toFixed(2) + ' km';
                    jarakHtml = '<span class="badge bg-primary ms-2" style="font-size: 0.7rem;"><i class="fa fa-location-arrow me-1"></i>' + jarakText + '</span>';
                }
                
                html += '<div class="map-result-item" onclick="focusMarker(' + d.id + ', ' + d.lat + ', ' + d.lon + ')">';
                html += '<div class="d-flex align-items-center">';
                html += '<img src="' + d.foto + '" class="rounded me-3" style="width: 70px; height: 70px; object-fit: cover;">';
                html += '<div class="flex-grow-1">';
                html += '<div class="d-flex justify-content-between align-items-start mb-1">';
                html += '<h6 class="mb-0 text-primary" style="font-size: 15px;">' + d.nama + '</h6>';
                html += jarakHtml;
                html += '</div>';
                html += '<small class="text-muted d-block" style="font-size: 12px; line-height: 1.2;"><i class="fa fa-map-marker-alt me-1"></i>' + (d.alamat ? d.alamat.substring(0, 40) + '...' : '-') + '</small>';
                html += '</div></div></div>';
            });
        }
        document.getElementById('resultList').innerHTML = html;
        document.getElementById('resultCount').innerText = data.length;
    }

    renderList(destinasi);

    // Search Feature
    document.getElementById('searchInput').addEventListener('input', function(e) {
        var keyword = e.target.value.toLowerCase();
        var filtered = destinasi.filter(function(d) {
            return d.nama.toLowerCase().includes(keyword) || (d.alamat && d.alamat.toLowerCase().includes(keyword));
        });
        renderList(filtered);
    });

    // Focus Marker
    function focusMarker(id, lat, lon) {
        map.flyTo([lat, lon], 16, {
            animate: true,
            duration: 1.5
        });
        
        // Open popup after animation
        setTimeout(function() {
            if(markers[id]) markers[id].openPopup();
        }, 1500);
        
        // On mobile, scroll down to map view
        if(window.innerWidth < 992) {
            document.getElementById('peta-wisata').scrollIntoView({behavior: 'smooth'});
        }
    }

    // Fungsi Haversine Javascript
    function hitungJarakKm(lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius bumi dalam km
        var dLat = (lat2 - lat1) * Math.PI / 180;
        var dLon = (lon2 - lon1) * Math.PI / 180;
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    /* ======================================================
       SKRIP TOMBOL "LOKASI SAYA" (GEOLOCATION & HAVERSINE JS)
       
       Alur kerja saat pengunjung menekan tombol Lokasi Saya di Sidebar:
       (1) Menjalankan sensor GPS (`navigator.geolocation`).
       (2) Jika berhasil, kamera peta akan terbang (`flyTo`) ke titik pengguna.
       (3) Sistem meletakkan Marker/Pin warna merah muda di posisi pengguna.
       (4) Melakukan perulangan (`forEach`) pada semua data wisata untuk 
           menghitung jaraknya dari posisi pengunjung menggunakan Rumus Haversine.
       (5) Menyortir (`sort`) data agar wisata yang paling dekat letaknya di atas.
       (6) Menulis ulang (`renderList`) tampilan daftar wisata di Sidebar kiri.
       (7) Mencari tahu teks alamat jalan pengguna melalui OpenStreetMap (Reverse Geocoding).
    ====================================================== */
    document.getElementById('btnLokasiSaya').addEventListener('click', function() {
        var btn = this;
        if (navigator.geolocation) {
            btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Mencari...';
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                
                map.flyTo([lat, lon], 14, { animate: true, duration: 1.5 });
                
                // Add marker for user location
                var userIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                
                // Hitung jarak ke tiap destinasi dan urutkan
                destinasi.forEach(function(d) {
                    if (d.lat && d.lon) {
                        d.jarak = hitungJarakKm(lat, lon, d.lat, d.lon);
                    }
                });
                destinasi.sort(function(a, b) {
                    return (a.jarak || 9999) - (b.jarak || 9999);
                });
                
                // Re-render daftar dengan urutan jarak terdekat
                var keyword = document.getElementById('searchInput').value.toLowerCase();
                var dataToRender = destinasi;
                if (keyword) {
                    dataToRender = destinasi.filter(function(d) {
                        return d.nama.toLowerCase().includes(keyword) || (d.alamat && d.alamat.toLowerCase().includes(keyword));
                    });
                }
                renderList(dataToRender);
                
                // Dapatkan alamat menggunakan Reverse Geocoding dari OpenStreetMap Nominatim API
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                    .then(response => response.json())
                    .then(data => {
                        var address = data.display_name || 'Alamat tidak ditemukan';
                        var popupContent = '<b class="text-danger d-block mb-1">Lokasi Anda Saat Ini</b>' +
                                           '<p class="mb-1 small" style="line-height: 1.3;"><i class="fa fa-map-marker-alt me-1 text-primary"></i> ' + address + '</p>' +
                                           '<p class="mb-0 small text-muted" style="line-height: 1.2;"><b>Lat:</b> ' + lat.toFixed(6) + '<br><b>Lon:</b> ' + lon.toFixed(6) + '</p>';
                        
                        L.marker([lat, lon], {icon: userIcon})
                            .addTo(map)
                            .bindPopup(popupContent)
                            .openPopup();
                        
                        btn.innerHTML = '<i class="fa fa-crosshairs me-1"></i>Lokasi Saya';
                    })
                    .catch(error => {
                        var popupContent = '<b class="text-danger d-block mb-1">Lokasi Anda Saat Ini</b>' +
                                           '<p class="mb-1 small text-muted">Alamat gagal dimuat.</p>' +
                                           '<p class="mb-0 small text-muted" style="line-height: 1.2;"><b>Lat:</b> ' + lat.toFixed(6) + '<br><b>Lon:</b> ' + lon.toFixed(6) + '</p>';
                                           
                        L.marker([lat, lon], {icon: userIcon})
                            .addTo(map)
                            .bindPopup(popupContent)
                            .openPopup();
                            
                        btn.innerHTML = '<i class="fa fa-crosshairs me-1"></i>Lokasi Saya';
                    });
            }, function(error) {
                alert('Gagal mendapatkan lokasi Anda: ' + error.message);
                btn.innerHTML = '<i class="fa fa-crosshairs me-1"></i>Lokasi Saya';
            });
        } else {
            alert('Browser Anda tidak mendukung fitur lokasi (Geolocation).');
        }
    });
</script>

<?= $this->include('pengunjung/layout/footer') ?>
