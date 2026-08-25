<?php

declare(strict_types=1);

/**
 * Helper pengaturan website (tabel `pengaturan`, pola key-value).
 *
 * Dipakai khusus untuk background hero:
 * - hero_index_1 / hero_index_2 / hero_index_3 : gambar slide carousel beranda
 * - hero_umum                                  : background breadcrumb semua halaman sub
 *
 * Jika admin belum mengunggah gambar (atau file fisiknya hilang),
 * helper akan mengembalikan gambar default bawaan template.
 */

if (! function_exists('pengaturan_ambil')) {
    /**
     * Ambil nilai pengaturan dari database berdasarkan kunci.
     * Hasil di-cache statis agar query tidak berulang dalam satu request.
     */
    function pengaturan_ambil(string $kunci): ?string
    {
        static $cache = [];

        if (! array_key_exists($kunci, $cache)) {
            $row = db_connect()
                ->table('pengaturan')
                ->select('nilai')
                ->where('kunci', $kunci)
                ->get()
                ->getFirstRow('array');

            $cache[$kunci] = $row['nilai'] ?? null;
        }

        return $cache[$kunci];
    }
}

if (! function_exists('hero_url')) {
    /**
     * URL gambar hero: pakai hasil upload admin jika ada dan file-nya
     * benar-benar tersedia, selain itu fallback ke gambar template lama.
     */
    function hero_url(string $kunci, string $default): string
    {
        $nilai = pengaturan_ambil($kunci);

        if ($nilai && file_upload_ada('hero', $nilai)) {
            return base_url('uploads/hero/' . $nilai);
        }

        return base_url($default);
    }
}
