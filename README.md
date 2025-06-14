# Kozmetik Stok Takip Sistemi

Bu proje, kozmetik ürünlerin stok yönetimini kolaylaştırmak için geliştirilmiş bir web tabanlı uygulamadır. PHP ve MySQL kullanılarak oluşturulmuştur ve kullanıcı yönetimi, ürün yönetimi, kategori yönetimi, tedarikçi yönetimi ve stok raporlaması gibi temel işlevsellikleri içerir. Bootstrap 5.3.3 ve Font Awesome 6.5.2 ile modern, kullanıcı dostu ve mobil uyumlu bir arayüz sunulur. Bu proje, bir ödev kapsamında phpMyAdmin ve FTP kullanılarak bir web sunucusuna yüklenecek şekilde tasarlanmıştır.

Siteyi URL : http://95.130.171.20/~st22360859088/index.php <br />
Tanıtım Youtube Video URL:

## Özellikler

- **Kullanıcı Yönetimi**:
  - Kullanıcı kaydı (`kayit.php`) ve girişi (`giris.php`).
  - Oturum kapatma (`cikis.php`).
  - Şifre güvenliği için BCRYPT hash kullanımı.

- **Ürün Yönetimi**:
  - Yeni ürün ekleme (`urun_ekle.php`).
  - Ürün düzenleme (`urun_duzenle.php`).
  - Ürün silme (`urun_sil.php`).
  - Ürün listeleme ve arama (`urunleri_listele.php`).

- **Raporlama**:
  - Genel stok özeti, kategori bazlı stok detayı ve düşük stoklu ürünler (`raporlar.php`).

- **Dashboard**:
  - Kullanıcıya özel karşılama ekranı, toplam ürün, kategori ve düşük stok istatistikleri (`dashboard.php`).

- **Güvenlik**:
  - XSS önleme için `guvenliYazi()` fonksiyonu.
  - SQL injection önleme için `mysqli_real_escape_string()` ve hazırlanmış ifadeler.
  - Benzersiz kullanıcı adı ve e-posta kontrolü.

- **Responsive Tasarım**:
  - Bootstrap ile mobil uyumlu arayüz.
  - Animasyonlu geçiş efektleri (`animate-fade-in`).

- **Veritabanı Yönetimi**:
  - MySQL tabanlı veritabanı (`db.sql`).
  - Kullanıcılar, kategoriler, ürünler ve tedarikçiler için yapılandırılmış tablolar.

## Gereksinimler

- **Sunucu**: PHP 7.4 veya üstü destekleyen bir web sunucusu (örn. Apache).
- **Veritabanı**: MySQL 5.7 veya üstü, phpMyAdmin ile yönetilebilir.
- **Bağımlılıklar**:
  - Bootstrap 5.3.3 (CDN üzerinden).
  - Font Awesome 6.5.2 (CDN üzerinden).
  - Google Fonts (Inter ve Poppins, CDN üzerinden).
- **Erişim**:
  - phpMyAdmin erişimi (veritabanı oluşturma ve SQL dosyası yükleme için).
  - FTP istemcisi (örn. FileZilla) ve sunucu erişim bilgileri.

## Kurulum
Bu bölüm, phpMyAdmin ve FTP kullanarak sistemin bir web sunucusuna nasıl yükleneceğini açıklar.

### 1. Veritabanını phpMyAdmin ile Kurma
1. **phpMyAdmin'e Giriş Yapın**:
   - Tarayıcınızda phpMyAdmin arayüzüne gidin (örn. `http://sunucu_adresi/phpmyadmin`).
   - Sunucu tarafından sağlanan kullanıcı adı ve şifreyi kullanarak oturum açın.

2. **Veritabanı Oluşturun**:
   - phpMyAdmin'de sol üst köşedeki **"Yeni"** (New) butonuna tıklayın.
   - Veritabanı adı olarak `kozmetik` yazın ve **Oluştur** (Create) butonuna tıklayın.

3. **SQL Dosyasını İçe Aktarın**:
   - Oluşturduğunuz `kozmetik` veritabanını seçin.
   - Üst menüden **İçe Aktar** (Import) sekmesine tıklayın.
   - `db.sql` dosyasını seçin (proje klasörünüzdeki `db.sql`).
   - **Git** (Go) butonuna tıklayarak SQL dosyasını çalıştırın.
   - Bu işlem, `kullanicilar`, `kategoriler`, `urunler` ve `tedarikciler` tablolarını oluşturacak ve örnek verileri yükleyecektir.

4. **Veritabanı Bağlantısını Kontrol Edin**:
   - `db.php` dosyasındaki bağlantı ayarlarını sunucunuza göre güncelleyin:
     ```php
     $sunucu = "localhost"; // Sunucu adresi (genellikle localhost)
     $kullanici = "veritabani_kullanici_adi"; // phpMyAdmin kullanıcı adı
     $sifre = "veritabani_sifresi"; // phpMyAdmin şifresi
     $veritabani = "kozmetik"; // Veritabanı adı
     ```
   - Sunucu sağlayıcınız farklı bir MySQL sunucu adresi veya kimlik bilgileri verdiyse, bunları kullanın.

## 📁 Dosya Yapısı
```
/kozmetik-stok/
├── index.php → Oturum kontrolüyle yönlendirme
├── giris.php → Giriş formu
├── kayit.php → Kayıt formu
├── cikis.php → Oturumu kapatma
├── dashboard.php → Ana kontrol paneli
├── urun_ekle.php → Ürün ekleme formu
├── urun_duzenle.php → Ürün düzenleme
├── urun_sil.php → Ürün silme
├── urunleri_listele.php → Ürün listeleme ve arama
├── raporlar.php → Stok raporları
│
├── includes/
│ ├── db.php → Veritabanı bağlantısı
│ ├── functions.php → Yardımcı PHP fonksiyonları
│ ├── auth.php → Kimlik doğrulama ve oturum yönetimi
│ ├── header.php → Ortak üst menü ve başlıklar
│ └── db.sql → Veritabanı yapısı ve örnek veriler
|
├── img/ → Resimler
|
└── README.md → Proje tanıtımı ve kullanım açıklamaları
```

## Dosya Yapısı ve Açıklamalar
- **kayit.php**:
  - Yeni kullanıcı kaydı için form. Kullanıcı adı ve e-posta benzersizliğini kontrol eder, şifreyi BCRYPT ile hash'ler ve başarılı kayıt sonrası `giris.php`'ye yönlendirir.

- **giris.php**:
  - Kullanıcı giriş formu. `kullaniciGiris()` fonksiyonu ile kimlik doğrulama yapar ve başarılı girişte `dashboard.php`'ye yönlendirir.

- **index.php**:
  - Giriş noktası. Oturum durumuna göre kullanıcıyı `dashboard.php` veya `giris.php`'ye yönlendirir.

- **cikis.php**:
  - Oturumu kapatır (`cikisYap()`) ve kullanıcıyı `giris.php`'ye yönlendirir.

- **dashboard.php**:
  - Kullanıcıya özel karşılama ekranı. Toplam ürün, kategori ve düşük stok sayısını gösterir. Son eklenen ve düşük stoklu ürünleri listeler.
  
- **urun_ekle.php**:
  - Yeni ürün ekleme formu. Ürün adı, barkod, kategori, marka, model, renk, miktar, min. stok, alış/satış fiyatı ve notlar alır. Barkod benzersizliğini kontrol eder.

- **urun_duzenle.php**:
  - Mevcut ürünü düzenleme formu. Ürün ID'sine göre bilgileri çeker ve günceller.

- **urun_sil.php**:
  - Ürün silme işlemi. Ürün ID'sini alır ve veritabanından siler.

- **urunleri_listele.php**:
  - Ürünleri tablo halinde listeler. Arama fonksiyonu ile ürün adı veya barkod bazlı filtreleme yapar.

- **raporlar.php**:
  - Stok raporları sunar: genel stok özeti, kategori bazlı detaylar ve düşük stoklu ürünler.

- **auth.php**:
  - Kimlik doğrulama fonksiyonları: `girisKontrol()`, `kullaniciGiris()`, `kullaniciKayit()`, `cikisYap()`.

- **db.php**:
  - Veritabanı bağlantısı ve site ayarlarını tanımlar (`SITE_NAME` = "Kozmetik Stok Takip Sistemi").

- **functions.php**:
  - Yardımcı fonksiyonlar: şifre hash'leme (`hashPassword`), güvenli yazı çıktısı (`guvenliYazi`), tarih formatlama (`tarihFormat`), stok durumu ve renk belirleme (`stokDurumu`, `stokRengi`), kategorileri ve düşük stoklu ürünleri getirme (`kategorileriGetir`, `dusukStokUrunler`), son kullanma tarihi yaklaşan ürünleri listeleme (`sonKullanmaYaklasan`).

- **header.php**:
  - Ortak HTML başlığı ve navigasyon çubuğu. Bootstrap, Font Awesome ve Google Fonts'u içerir.

- **db.sql**:
  - Veritabanı şeması ve örnek veriler. `kullanicilar`, `kategoriler`, `urunler` ve `tedarikciler` tablolarını tanımlar.

## Veritabanı Şeması
- **kullanicilar**:
  - `id`, `kullanici_adi`, `sifre`, `tam_isim`, `email`, `yetki`, `kayit_tarihi`, `son_giris`, `aktif`.
- **kategoriler**:
  - `id`, `kategori_adi`, `aciklama`, `olusturma_tarihi`.
- **urunler**:
  - `id`, `urun_adi`, `barkod`, `kategori_id`, `marka`, `model`, `renk`, `miktar`, `min_stok`, `birim`, `alis_fiyati`, `satis_fiyati`, `son_kullanma_tarihi`, `aciklama`, `resim`, `ekleme_tarihi`, `guncellenme_tarihi`, `ekleyen_kullanici`, `aktif`.
- **tedarikciler**:
  - `id`, `firma_adi`, `yetkili_kisi`, `telefon`, `email`, `adres`, `vergi_no`, `notlar`, `eklenme_tarihi`, `aktif`.

## Kullanım

1. **Kayıt Olma**:
   - `kayit.php` üzerinden yeni kullanıcı hesabı oluşturun.
   
   ![Kayıt Formu Ekran Görüntüsü](https://github.com/korayga/kozmetik/blob/main/img/img0.png)

2. **Giriş Yapma**:
   - `giris.php` üzerinden kullanıcı adı ve şifre ile giriş yapın.

   ![Giriş Formu Ekran Görüntüsü](https://github.com/korayga/kozmetik/blob/main/img/img1.png)

3. **Ürün Yönetimi**:
   - `urun_ekle.php`: Yeni ürün ekleyin.
   - `urunleri_listele.php`: Ürünleri görüntüleyin, arayın, düzenleyin veya silin.
   - `urun_duzenle.php`: Mevcut ürünü güncelleyin.
   - `urun_sil.php`: Ürünü silin.

   ![Urun İslemleri Ekran Görüntüsü](https://github.com/korayga/kozmetik/blob/main/img/img3.png)
  
4. **Raporlama**:
   - `raporlar.php`: Stok durumunu analiz edin.

   ![Raporlar Ekran Görüntüsü](https://github.com/korayga/kozmetik/blob/main/img/img4.png)

5. **Dashboard**:
   - `dashboard.php`: Genel istatistikleri ve özet bilgileri görüntüleyin.

     ![Anasayfa Ekran Görüntüsü](https://github.com/korayga/kozmetik/blob/main/img/img2.png)

6. **Çıkış Yapma**:
   - Navigasyon çubuğundaki "Çıkış Yap" seçeneği ile oturumu kapatın.


## Güvenlik Notları

- **SQL Injection**: `mysqli_real_escape_string()` ve hazırlanmış ifadeler (`mysqli_prepare`) kullanılarak SQL enjeksiyonu önlenir.
- **XSS**: `guvenliYazi()` fonksiyonu ile kullanıcı girişleri HTML özel karakterlere dönüştürülür.
- **Şifre Güvenliği**: Şifreler BCRYPT ile hash'lenir.
- **Oturum Yönetimi**: Oturum açılmadan korunan sayfalara erişim engellenir (`girisKontrol()`).

## 👥 Geliştirici
E-posta: koraygarip@gmail.com <br />
GitHub: [https://github.com/korayga]
