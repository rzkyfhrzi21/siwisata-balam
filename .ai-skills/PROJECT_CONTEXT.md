---
name: project-context
description: >
  Knowledge Base & Cetak Biru proyek SiWisata Balam.
  Berisi daftar file referensi wajib baca, konvensi routing (slug vs ID),
  standar UI/UX (skeleton loading, tombol aksi), dan aturan-aturan teknis
  yang harus dipatuhi sebelum membuat fitur baru.
---

# PROJECT CONTEXT & KNOWLEDGE BASE

Setiap kali user memberikan perintah `start @[.ai-skills/AI_RULES.md]`, AI **WAJIB** secara otomatis melakukan sinkronisasi dengan membaca daftar file cetak biru proyek (termasuk Desain Database) berikut:

1. `docs/PRD.md` (Untuk memahami spesifikasi fitur dan tujuan akhir aplikasi).
2. `docs/STRUKTUR_PROJECT.md` (Untuk memahami tata letak file dan struktur direktori).
3. `docs/DATABASE_DESIGN.md` (Untuk memahami skema database, relasi tabel, dan logika Haversine — **acuan utama database**).
4. `docs/travela/` (Template HTML untuk halaman depan/klien — acuan UI/UX frontend pengunjung).
5. `docs/adminlte4/` (Template AdminLTE 4 untuk halaman admin — acuan UI/UX backend panel CRUD).
6. **URL Routing Convention:**
   - Gunakan `slug` (bukan ID) untuk semua URL yang bertugas **menampilkan (view) halaman/data** kepada pengunjung (contoh: `/wisata/nama-wisata`). Ini wajib untuk halaman front-end demi SEO.
   - Gunakan `ID` (numerik) HANYA untuk rute manipulasi data/CRUD (seperti `update`, `delete`, `edit`) di backend dashboard admin.
7. **Standar UI/UX - Skeleton Lazy Loading:**
   - Setiap tag `<img>` pada antarmuka publik (front-end) **WAJIB** mengimplementasikan teknik skeleton lazy loading.
   - Standar atribut: `class="... skeleton-effect"` ditambah `loading="lazy" onload="this.classList.remove('skeleton-effect')"`.
8. **Standar UI/UX - Tombol Aksi Klien (.btn-jelajah)**:
   - Tombol aksi utama (seperti _Baca Detail_, _Menuju Lokasi_) **WAJIB** memakai _class_ `.btn-jelajah`.
   - **Kondisi Default:** Teks putih (`#ffffff`), Latar Biru (`#13357B`).
   - **Kondisi Hover:** Teks Biru (`#13357B`), Latar Putih (`#ffffff`).

Sebelum membuat fitur baru atau merombak arsitektur, patokan utama wajib mengacu pada file-file di atas.
