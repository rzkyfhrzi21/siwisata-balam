<?php

declare(strict_types=1);

/**
 * Helper penanganan gambar/video upload dengan fallback.
 *
 * Semua tampilan yang menampilkan file dari folder public/uploads/
 * wajib melewati helper ini agar file yang hilang di server
 * otomatis diganti dengan gambar fallback (logo website).
 */

if (! defined('FALLBACK_GAMBAR_PATH')) {
    define('FALLBACK_GAMBAR_PATH', 'uploads/fallback/logo.svg');
}

if (! function_exists('file_upload_ada')) {
    /**
     * Cek apakah file ada di folder public/uploads/{folder}/{filename}.
     */
    function file_upload_ada(?string $folder, ?string $filename): bool
    {
        if ($filename === null || $filename === '') {
            return false;
        }

        $path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . trim($folder, '/\\') . DIRECTORY_SEPARATOR . $filename;

        return is_file($path);
    }
}

if (! function_exists('gambar_url')) {
    /**
     * Ambil URL file upload; jika file tidak ditemukan,
     * kembalikan URL gambar fallback (logo website).
     */
    function gambar_url(?string $folder, ?string $filename): string
    {
        if (file_upload_ada($folder, $filename)) {
            return base_url('uploads/' . trim((string) $folder, '/') . '/' . $filename);
        }

        return base_url(FALLBACK_GAMBAR_PATH);
    }
}
