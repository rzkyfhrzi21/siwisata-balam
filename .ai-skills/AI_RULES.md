---
name: ai-master-rules
description: >
  File induk aturan AI untuk proyek SiWisata Balam.
  Seluruh skill telah dipecah ke file masing-masing di folder .ai-skills/
  agar lebih modular dan mudah di-maintain.
---

# DAFTAR SKILLS AI — SiWisata Balam

Berikut adalah daftar seluruh skill yang harus dibaca dan dipatuhi oleh AI.
Setiap skill disimpan di file terpisah dalam folder `.ai-skills/`:

| # | File | Nama Skill | Deskripsi Singkat |
|---|------|-----------|-------------------|
| 1 | `CAVEMAN_SKILL.md` | Caveman Skill | Gaya komunikasi ultra-ringkas ala "smart caveman". Mengurangi token ~75%. |
| 2 | `PONYTAIL_SKILL.md` | Ponytail Skill | Lazy Senior Dev Mode — solusi paling efisien dan minimal (YAGNI). |
| 3 | `PROJECT_CONTEXT.md` | Project Context & Knowledge Base | Cetak biru proyek, daftar dokumen wajib baca, konvensi routing, standar UI/UX. |
| 4 | `WORKFLOW_MODE.md` | Instructor & Eksekutor Mode | Dua mode kerja: Instructor (AI membimbing) dan Eksekutor (AI langsung ubah kode + wajib lapor). |
| 5 | `KOMENTAR_ORANG_TUA.md` | Komentar Orang Tua | Standar penulisan komentar kode (Model, View, Controller, Script JS) dengan bahasa awam. |

## Cara Kerja

- **Semua skill aktif secara bersamaan** kecuali ada konflik (misal: Caveman vs Komentar Orang Tua → komentar dalam kode tetap pakai gaya Orang Tua, hanya respons chat yang pakai Caveman).
- Untuk memulai sesi, user cukup mengetik `start @[.ai-skills/AI_RULES.md]` dan AI akan membaca file ini beserta seluruh skill yang terdaftar.
- Skill bisa ditambah/dihapus dengan membuat/menghapus file `.md` di folder ini dan memperbarui tabel di atas.
