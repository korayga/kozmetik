<?php
// Giriş kontrolü
function girisKontrol() {
    if (!isset($_SESSION['kullanici_id'])) {
        header('Location: giris.php'); 
    }
}


// Kullanıcı giriş yapma
function kullaniciGiris($baglanti, $kullanici_adi, $sifre) {
   
    $sorgu = "SELECT * FROM kullanicilar WHERE kullanici_adi = '$kullanici_adi'";
    $sonuc = mysqli_query($baglanti, $sorgu);

    if (mysqli_num_rows($sonuc) == 1) {
        $kullanici = mysqli_fetch_array($sonuc);
        
        // Şifreyi doğrula
        if (password_verify($sifre, $kullanici['sifre'])) {
            $_SESSION['kullanici_id'] = $kullanici['id'];
            $_SESSION['kullanici_adi'] = $kullanici['kullanici_adi'];
            $_SESSION['kullanici_yetki'] = $kullanici['yetki'];
            $_SESSION['tam_isim'] = $kullanici['tam_isim'];
            
            return true;
        }
    }
    
    return false;
}

// Kullanıcı kayıt
function kullaniciKayit($baglanti, $kullanici_adi, $sifre, $tam_isim, $email) {
  
    $kontrol = "SELECT * FROM kullanicilar WHERE kullanici_adi = '$kullanici_adi'";  // Kullanıcı adı kontrol
    $sonuc = mysqli_query($baglanti, $kontrol);
    
    if (mysqli_num_rows($sonuc) > 0) {
        return false; // Kullanıcı adı zaten var
    }
    
    
    $kontrol2 = "SELECT * FROM kullanicilar WHERE email = '$email'";  // Email  kontrol
    $sonuc2 = mysqli_query($baglanti, $kontrol2);
    
    if (mysqli_num_rows($sonuc2) > 0) {
        return false; // Email zaten var
    }
    
    
    $hashed_sifre = hashPassword($sifre); 
    $tarih = date('Y-m-d H:i:s');
    
    $sorgu = "INSERT INTO kullanicilar (kullanici_adi, sifre, tam_isim, email, yetki, kayit_tarihi) 
              VALUES ('$kullanici_adi', '$hashed_sifre', '$tam_isim', '$email', 'kullanici', '$tarih')";
    
    if (mysqli_query($baglanti, $sorgu)) {
        return true;
    }
    
    return false;
}

// Çıkış yapma
function cikisYap() {
    session_destroy();
    header('Location: giris.php'); 
    exit();
}


?>