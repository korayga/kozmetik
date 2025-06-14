<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Kullanıcının giriş yapıp yapmadığını kontrol et
girisKontrol();



// Genel Stok Özeti
$sorgu_total_products = mysqli_query($baglanti, "SELECT COUNT(*) as total FROM urunler WHERE aktif = 1");
$total_products = mysqli_fetch_assoc($sorgu_total_products)['total'] ?? 0;

$sorgu_total_stock = mysqli_query($baglanti, "SELECT SUM(miktar) as total_stock FROM urunler WHERE aktif = 1");
$total_stock = mysqli_fetch_assoc($sorgu_total_stock)['total_stock'] ?? 0;

$sorgu_total_value = mysqli_query($baglanti, "SELECT SUM(miktar * alis_fiyati) as total_value FROM urunler WHERE aktif = 1");
$total_value = mysqli_fetch_assoc($sorgu_total_value)['total_value'] ?? 0;

// Kategori Bazlı Stok Özeti
$category_summary_sorgu = mysqli_query($baglanti, "SELECT k.kategori_adi, SUM(u.miktar) as toplam_miktar, COUNT(u.id) as urun_sayisi
                                                    FROM urunler u
                                                    JOIN kategoriler k ON u.kategori_id = k.id
                                                    WHERE u.aktif = 1
                                                    GROUP BY k.kategori_adi
                                                    ORDER BY k.kategori_adi ASC");
$category_summary = [];
while($row = mysqli_fetch_assoc($category_summary_sorgu)) {
    $category_summary[] = $row;
}

// Düşük Stoklu Ürünler
$low_stock_products = dusukStokUrunler($baglanti); 

// Header'ı dahil et
require_once 'includes/header.php';
?>

<style>
    .report-page-container {
        background-color: #ffffff; /* Beyaz arka plan */
        border-radius: 1rem;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-top: 1rem;
        margin-bottom: 2rem;
        margin-left: 2rem;
        margin-right: 2rem;
    }
    @media (max-width: 991.98px) {
        .report-page-container {
            margin-left: 1rem;
            margin-right: 1rem;
            padding: 1.5rem;
            border-radius: 0.5rem;
        }
    }

    .report-section {
        margin-bottom: 3rem; 
    }
    .report-section:last-child {
        margin-bottom: 0; 
    }

    .report-title {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(0, 123, 255, 0.2); 
        display: flex;
        align-items: center;
    }
    .report-title .fas {
        margin-right: 0.75rem;
    }

    .stat-box {
        background-color: var(--light-bg);
        border-radius: 0.75rem;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 1.5rem;
        box-shadow: inset 0 1px 5px rgba(0,0,0,0.03);
    }
    .stat-box .value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--dark-text);
        line-height: 1.2;
    }
    .stat-box .label {
        font-size: 1rem;
        color: var(--secondary-color);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 0.5rem;
    }
    .table thead th {
        background-color: var(--light-bg); 
    }
</style>

<div class="container-fluid report-page-container animate-fade-in">
    <h1 class="mb-4 text-center text-md-start">
        <i class="fas fa-file-alt me-2 text-primary"></i>Raporlar
    </h1>
    
    <div class="report-section">
        <h2 class="report-title"><i class="fas fa-info-circle"></i>Genel Stok Özeti</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="value"><?php echo $total_products; ?></div>
                    <div class="label">Toplam Ürün</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="value"><?php echo $total_stock; ?></div>
                    <div class="label">Toplam Stok Miktarı</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="value"><?php echo number_format($total_value, 2, ',', '.'); ?> TL</div>
                    <div class="label">Toplam Stok Değeri</div>
                </div>
            </div>
        </div>
    </div>

    <div class="report-section">
        <h2 class="report-title"><i class="fas fa-tags"></i>Kategori Bazlı Stok Detayı</h2>
        <?php if (!empty($category_summary)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Kategori Adı</th>
                            <th>Toplam Miktar</th>
                            <th>Ürün Sayısı</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($category_summary as $item): ?>
                            <tr>
                                <td><?php echo guvenliYazi($item['kategori_adi']); ?></td>
                                <td><?php echo $item['toplam_miktar']; ?></td>
                                <td><?php echo $item['urun_sayisi']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle me-2"></i>Henüz kategori bazlı veri bulunmuyor.
            </div>
        <?php endif; ?>
    </div>

    <div class="report-section">
        <h2 class="report-title"><i class="fas fa-exclamation-triangle text-warning"></i>Düşük Stoklu Ürünler</h2>
        <?php if (!empty($low_stock_products)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Ürün Adı</th>
                            <th>Mevcut Stok</th>
                            <th>Minimum Stok</th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($low_stock_products as $urun): ?>
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
            <div class="text-end mt-3">
                 <a href="urunleri_listele.php?filter=dusuk-stok" class="btn btn-outline-primary btn-sm">Tüm Düşük Stoklu Ürünleri Gör <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        <?php else: ?>
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle me-2"></i>Tüm ürünlerinizin stoğu yeterli görünüyor!
            </div>
        <?php endif; ?>
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