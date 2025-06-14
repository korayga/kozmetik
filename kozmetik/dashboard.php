<?php
// Gerekli dosyaları dahil et
require_once 'includes/db.php';       // Veritabanı bağlantısı ve SITE_NAME
require_once 'includes/functions.php'; // Yardımcı fonksiyonlar
require_once 'includes/auth.php';      // Kimlik doğrulama fonksiyonları

// Kullanıcının giriş yapıp yapmadığını kontrol et
girisKontrol();

// Dashboard için gerekli istatistikleri veritabanından çekme
$sorgu_toplam_urun = mysqli_query($baglanti, "SELECT COUNT(*) as sayi FROM urunler WHERE aktif = 1");
$toplam_urun = mysqli_fetch_array($sorgu_toplam_urun)['sayi'];

$sorgu_toplam_kategori = mysqli_query($baglanti, "SELECT COUNT(*) as sayi FROM kategoriler");
$toplam_kategori = mysqli_fetch_array($sorgu_toplam_kategori)['sayi'];

$sorgu_dusuk_stok = mysqli_query($baglanti, "SELECT COUNT(*) as sayi FROM urunler WHERE miktar <= min_stok AND aktif = 1");
$dusuk_stok = mysqli_fetch_array($sorgu_dusuk_stok)['sayi'];

// Toplam değeri hesaplama
$sorgu_toplam_deger = mysqli_query($baglanti, "SELECT SUM(miktar * alis_fiyati) as toplam FROM urunler WHERE aktif = 1");
$toplam_deger = mysqli_fetch_array($sorgu_toplam_deger)['toplam'];

// Düşük stoklu ürünlerin detaylarını getirme
$dusuk_stok_urunler = dusukStokUrunler($baglanti);

// Son eklenen 5 aktif ürünü getirme
$son_urunler_sorgu = mysqli_query($baglanti, "SELECT * FROM urunler WHERE aktif = 1 ORDER BY ekleme_tarihi DESC LIMIT 5");
$son_urunler = array();
while ($satir = mysqli_fetch_array($son_urunler_sorgu)) {
    $son_urunler[] = $satir;
}

// Sayfanın başlığını ve genel stilini içeren header dosyasını dahil etme
require_once 'includes/header.php';
?>

<style>
    /* Dashboard'un ilk bölümü için genel padding  */
    .dashboard-header-section {
        padding: 1.5rem;
        margin-top: 1rem; 
        margin-bottom: 2rem;
        color: white; 
        text-shadow: 0 1px 3px rgba(0,0,0,0.2); 
    }
   
    /* Yeni İstatistik Özeti Alanı Stili*/
    .stats-overview {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: center;
        margin-top: 2rem;
        margin-bottom: 3rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px dashed rgba(255, 255, 255, 0.4);
        font-family: 'Poppins', sans-serif;
    }

    .stat-display {
        flex: 1; 
        min-width: 200px; 
        text-align: center;
        padding: 1rem 0;
        position: relative;
    }
    
    .stat-display .value {
        font-size: 3.8rem; 
        font-weight: 700;
        color: #ffffff; 
        line-height: 1.1;
        margin-bottom: 0.25rem;
        text-shadow: 0 2px 5px rgba(0,0,0,0.3); 
    }

    .stat-display .label {
        font-size: 1.25rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9); 
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
   
</style>
<!--  Dashboard ana içerik başlangıcı -->
<div class="container-fluid">
    <div class="dashboard-header-section animate-fade-in">
        <h1 class="mb-2 text-center text-md-start">
            Hoş Geldiniz, <span class="text-white"><?php echo guvenliYazi(isset($_SESSION['tam_isim']) ? $_SESSION['tam_isim'] : $_SESSION['kullanici_adi']); ?></span>!
        </h1>

        <div class="stats-overview animate-fade-in" style="animation-delay: 0.2s;">
            <div class="stat-display">
                <div class="value"><?php echo $toplam_urun; ?></div>
                <div class="label">Toplam Ürün</div>
            </div>
            <div class="stat-display">
                <div class="value"><?php echo $toplam_kategori; ?></div>
                <div class="label">Toplam Kategori</div>
            </div>
            <div class="stat-display">
                <div class="value"><?php echo $dusuk_stok; ?></div>
                <div class="label">Düşük Stok</div>
            </div>
        </div>
    </div>

    <div class="dashboard-cards-container animate-fade-in" style="animation-delay: 0.4s;">
        <div class="row justify-content-center">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-arrow-down me-2 text-warning"></i>Düşük Stoklu Ürünler</h5>
                        <a href="urunleri_listele.php?filter=dusuk-stok" class="btn btn-sm btn-outline-primary">Tümünü Gör <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($dusuk_stok_urunler)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Ürün Adı</th>
                                            <th>Stok</th>
                                            <th>Min. Stok</th>
                                            <th>Durum</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($dusuk_stok_urunler as $urun): ?>
                                            <tr>
                                                <td><?php echo guvenliYazi($urun['urun_adi']); ?></td>
                                                <td><span class="badge bg-<?php echo stokRengi($urun['miktar'], $urun['min_stok']); ?>"><?php echo $urun['miktar']; ?></span></td>
                                                <td><?php echo $urun['min_stok']; ?></td>
                                                <td><span class="badge bg-<?php echo stokRengi($urun['miktar'], $urun['min_stok']); ?>"><?php echo stokDurumu($urun['miktar'], $urun['min_stok']); ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success m-3" role="alert">
                                <i class="fas fa-check-circle me-2"></i>Tüm ürünlerinizin stoğu yeterli görünüyor!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-plus-circle me-2 text-success"></i>Son Eklenen Ürünler</h5>
                        <a href="urunleri_listele.php" class="btn btn-sm btn-outline-primary">Tümünü Gör <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($son_urunler)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Ürün Adı</th>
                                            <th>Miktar</th>
                                            <th>Eklenme Tarihi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($son_urunler as $urun): ?>
                                            <tr>
                                                <td><?php echo guvenliYazi($urun['urun_adi']); ?></td>
                                                <td><?php echo $urun['miktar']; ?></td>
                                                <td><?php echo tarihFormat($urun['ekleme_tarihi']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info m-3" role="alert">
                                <i class="fas fa-info-circle me-2"></i>Henüz eklenmiş ürün bulunmuyor.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>

<script>
    // animasyonlar için JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.animate-fade-in');
        elements.forEach((el, index) => {
            el.style.opacity = '0'; 
            el.style.transform = 'translateY(20px)'; 
            setTimeout(() => {
                 el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 150); 
        });
    });
</script>
</body>
</html>