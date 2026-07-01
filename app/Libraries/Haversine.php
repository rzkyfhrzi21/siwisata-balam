<?php

namespace App\Libraries;

/* ======================================================
   LIBRARY KHUSUS: ALGORITMA HAVERSINE
   
   Apa itu Library ini?
   Bayangkan bumi itu bulat seperti bola. Jika kita ingin mengukur jarak 
   lurus antara dua titik di atas permukaan bola, kita tidak bisa memakai 
   rumus garis lurus biasa (Pythagoras). Kita butuh rumus trigonometri 
   yang memperhitungkan lengkungan bumi. Rumus itulah yang dinamakan 
   "Algoritma Haversine".

   File ini sengaja dipisah secara mandiri (modular) agar logika 
   perhitungan matematika ini rapi dan tidak mengotori kodingan utama.
====================================================== */
class Haversine
{
    /**
     * Jari-jari (Radius) rata-rata bumi dalam ukuran Kilometer.
     * Angka 6371 adalah konstanta patokan standar internasional.
     */
    public const EARTH_RADIUS_KM = 6371;

    /* ======================================================
       FUNGSI HITUNG JARAK (calculateDistance)
       Fungsi ini layaknya mesin kalkulator. Kita masukkan koordinat asal 
       dan koordinat tujuan, lalu dia akan memuntahkan angka jaraknya.
    ====================================================== */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        // (1) PENERIMAAN DATA KOORDINAT (Parameter float = desimal/angka koma)
        // $lat1 & $lon1 = Garis lintang dan bujur lokasi si pengguna / pengunjung saat ini.
        // $lat2 & $lon2 = Garis lintang dan bujur lokasi wisata tujuan yang ada di database.
        
        // (2) KONVERSI DERAJAT KE RADIAN
        // GPS biasanya menggunakan format "Derajat" (Degree).
        // Tapi fungsi matematika bawaan bahasa PHP (sin, cos, atan2) hanya mau menerima format "Radian".
        // Maka, kita hitung dulu selisih jarak lintang dan selisih jarak bujurnya, 
        // lalu ubah hasilnya menjadi radian dengan perintah 'deg2rad'.
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        // (3) PROSES RUMUS TRIGONOMETRI (INTI HAVERSINE)
        // Variabel $a menghitung kuadrat dari setengah tali busur di antara titik tersebut.
        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        // Variabel $c menghitung sudut (dalam radian) yang dibentuk antara dua titik tersebut,
        // jika diukur dari pusat bumi.
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // (4) KALKULASI HASIL AKHIR (Dalam bentuk Kilometer)
        // Sudut bumi ($c) dikalikan dengan Jari-jari Bumi (6371 KM)
        $distance = self::EARTH_RADIUS_KM * $c;

        // (5) PERAPIHAN ANGKA / PEMBULATAN
        // Hasil dari rumus biasanya sangat panjang (misal: 15.54891238912 KM).
        // Kita gunakan 'round($distance, 2)' untuk memotongnya jadi 2 angka di belakang koma saja.
        // Hasilnya jadi cantik: 15.55 (KM) lalu dikembalikan (return) ke yang memanggil fungsi ini.
        return round($distance, 2);
    }
}
