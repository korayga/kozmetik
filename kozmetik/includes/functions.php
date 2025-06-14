
<?php
// Güvenli şifre hash'leme
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}


// Güvenli yazı çıktısı
function guvenliYazi($metin) {
    return htmlspecialchars($metin, ENT_QUOTES, 'UTF-8');
}

// Tarih formatı
function tarihFormat($tarih) {
    return date('d.m.Y H:i', strtotime($tarih));
}

// Sayı formatı (para için)
function paraFormat($sayi) {
    return number_format($sayi, 2, ',', '.') . ' ₺';
}

// Stok durumu kontrolü
function stokDurumu($miktar, $minStok) {
    if ($miktar <= 0) {
        return "Tükendi";
    } elseif ($miktar <= $minStok) {
        return "Düşük";
    } else {
        return "Normal";
    }
}

// Stok rengi
function stokRengi($miktar, $minStok) {
    if ($miktar <= 0) {
        return "danger";
    } elseif ($miktar <= $minStok) {
        return "warning";
    } else {
        return "success";
    }
}


// Kategorileri getir
function kategorileriGetir($baglanti) {
    $sorgu = "SELECT * FROM kategoriler ORDER BY kategori_adi";
    $sonuc = mysqli_query($baglanti, $sorgu);
    $kategoriler = array();
    
    while ($satir = mysqli_fetch_array($sonuc)) {
        $kategoriler[] = $satir;
    }
    
    return $kategoriler;
}

// Düşük stoklu ürünleri getir
function dusukStokUrunler($baglanti) {
    $sorgu = "SELECT * FROM urunler WHERE miktar <= min_stok ORDER BY miktar ASC";
    $sonuc = mysqli_query($baglanti, $sorgu);
    $urunler = array();
    
    while ($satir = mysqli_fetch_array($sonuc)) {
        $urunler[] = $satir;
    }
    
    return $urunler;
}

// Son kullanma tarihi yaklaşan ürünler
function sonKullanmaYaklasan($baglanti, $gun = 30) {
    $tarih = date('Y-m-d', strtotime("+$gun days"));
    $sorgu = "SELECT * FROM urunler WHERE son_kullanma_tarihi <= '$tarih' AND son_kullanma_tarihi >= CURDATE() ORDER BY son_kullanma_tarihi ASC";
    $sonuc = mysqli_query($baglanti, $sorgu);
    $urunler = array();
    
    while ($satir = mysqli_fetch_array($sonuc)) {
        $urunler[] = $satir;
    }
    
    return $urunler;
}
?>