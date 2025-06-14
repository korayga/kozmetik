

-- Kullanıcılar Tablosu
CREATE TABLE kullanicilar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kullanici_adi VARCHAR(50) UNIQUE NOT NULL,
    sifre VARCHAR(255) NOT NULL,
    tam_isim VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    yetki ENUM('admin', 'kullanici') DEFAULT 'kullanici',
    kayit_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
    son_giris DATETIME,
    aktif TINYINT(1) DEFAULT 1
);

-- Kategoriler Tablosu
CREATE TABLE kategoriler (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kategori_adi VARCHAR(100) NOT NULL,
    aciklama TEXT,
    olusturma_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Ürünler Tablosu
CREATE TABLE urunler (
    id INT PRIMARY KEY AUTO_INCREMENT,
    urun_adi VARCHAR(200) NOT NULL,
    barkod VARCHAR(50) UNIQUE,
    kategori_id INT,
    marka VARCHAR(100),
    model VARCHAR(100),
    renk VARCHAR(50),
    miktar INT DEFAULT 0,
    min_stok INT DEFAULT 5,
    birim VARCHAR(20) DEFAULT 'adet',
    alis_fiyati DECIMAL(10,2),
    satis_fiyati DECIMAL(10,2),
    son_kullanma_tarihi DATE,
    aciklama TEXT,
    resim VARCHAR(255),
    ekleme_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
    guncellenme_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ekleyen_kullanici INT,
    aktif TINYINT(1) DEFAULT 1,
    FOREIGN KEY (kategori_id) REFERENCES kategoriler(id),
    FOREIGN KEY (ekleyen_kullanici) REFERENCES kullanicilar(id)
);
CREATE TABLE tedarikciler(
  id int(11) NOT NULL,
  firma_adi varchar(200) NOT NULL,
  yetkili_kisi varchar(100) DEFAULT NULL,
  telefon varchar(20) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  adres  text DEFAULT NULL,
  vergi_no varchar(20) DEFAULT NULL,
  notlar text DEFAULT NULL,
  eklenme_tarihi datetime DEFAULT current_timestamp(),
  aktif tinyint(1) DEFAULT 1
);



-- Örnek Veriler Ekleme

INSERT INTO kullanicilar (kullanici_adi, sifre, tam_isim, email, yetki) 
VALUES ('admin', 'admin', 'Sistem Yöneticisi', 'admin@kozmetik.com', 'admin');

INSERT INTO kullanicilar (kullanici_adi, sifre, tam_isim, email, yetki) 
VALUES ('kullanici1', 'e10adc3949ba59abbe56e057f20f883e', 'Test Kullanıcısı', 'kullanici@test.com', 'kullanici');

-- Kategoriler
INSERT INTO kategoriler (kategori_adi, aciklama) VALUES 
('Cilt Bakımı', 'Yüz ve vücut bakım ürünleri'),
('Makyaj', 'Fondöten, ruj, maskara vb.'),
('Saç Bakımı', 'Şampuan, saç kremi, saç spreyi'),
('Parfüm', 'Kadın ve erkek parfümleri'),
('Güneş Koruyucu', 'SPF içeren koruyucu ürünler'),
('Temizlik', 'Yüz temizleyici, makyaj temizleyici');


-- Örnek Ürünler
INSERT INTO urunler (urun_adi, barkod, kategori_id, marka, miktar, min_stok, alis_fiyati, satis_fiyati, ekleyen_kullanici) VALUES 
('Nemlendirici Krem', '1234567890123', 1, 'LOreal', 25, 5, 45.50, 65.00, 1),
('Fondöten', '1234567890124', 2, 'Maybelline', 15, 3, 35.00, 55.00, 1),
('Şampuan 400ml', '1234567890125', 3, 'Head&Shoulders', 30, 10, 18.50, 28.00, 1),
('Kadın Parfümü 50ml', '1234567890126', 4, 'Chanel', 8, 2, 250.00, 380.00, 1),
('Güneş Kremi SPF30', '1234567890127', 5, 'Nivea', 20, 5, 22.00, 35.00, 1);

-- Örnek Tedarikçiler
INSERT INTO `tedarikciler` (`id`, `firma_adi`, `yetkili_kisi`, `telefon`, `email`, `adres`, `vergi_no`, `notlar`, `eklenme_tarihi`, `aktif`) VALUES
(1, 'ABC Kozmetik Ltd.', 'Ahmet Yılmaz', '0212-555-0001', 'info@abckozmetik.com', NULL, NULL, NULL, '2025-06-11 16:30:01', 1),
(2, 'XYZ Güzellik San.', 'Ayşe Demir', '0216-555-0002', 'satis@xyzguzellik.com', NULL, NULL, NULL, '2025-06-11 16:30:01', 1),
(3, 'Güzellik Dünyası', 'Mehmet Kaya', '0232-555-0003', 'siparis@guzellikdunyasi.com', NULL, NULL, NULL, '2025-06-11 16:30:01', 1);


