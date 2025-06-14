<?php
// Oturumu başlat
session_start();

// Site ayarları
define('SITE_NAME', 'Kozmetik Stok Takip Sistemi');

// Veritabanı ayarları
$sunucu = "localhost";
$kullanici = "root";
$sifre = "";
$veritabani = "kozmetik";

// MySQLi ile bağlantı
$baglanti = mysqli_connect($sunucu, $kullanici, $sifre, $veritabani);

// Bağlantı kontrolü
if (!$baglanti) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

// Türkçe karakter desteği
mysqli_set_charset($baglanti, "utf8");
?>