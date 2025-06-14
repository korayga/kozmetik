<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

girisKontrol();

// Arama parametresi
$arama = isset($_GET['arama']) ? trim($_GET['arama']) : '';

// Mesajları alma
$basari_mesaji = isset($_SESSION['basari_mesaji']) ? $_SESSION['basari_mesaji'] : '';
$hata_mesaji = isset($_SESSION['hata_mesaji']) ? $_SESSION['hata_mesaji'] : '';
unset($_SESSION['basari_mesaji'], $_SESSION['hata_mesaji']);

// Ürünleri getirme
$sql = "SELECT u.*, k.kategori_adi 
        FROM urunler u 
        LEFT JOIN kategoriler k ON u.kategori_id = k.id 
        WHERE u.aktif = 1";

if (!empty($arama)) {
    $sql .= " AND (u.urun_adi LIKE '%$arama%' OR u.barkod LIKE '%$arama%')";
}

$sql .= " ORDER BY u.id DESC";
$sonuc = mysqli_query($baglanti, $sql);

require_once 'includes/header.php';
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-boxes"></i> Ürünler Listesi</h5>
            <a href="urun_ekle.php" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Yeni Ürün
            </a>
        </div>
        <div class="card-body">
            <?php if ($basari_mesaji): ?>
                <div class="alert alert-success"><?php echo $basari_mesaji; ?></div>
            <?php endif; ?>
            <?php if ($hata_mesaji): ?>
                <div class="alert alert-danger"><?php echo $hata_mesaji; ?></div>
            <?php endif; ?>

            <!-- Arama Formu -->
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="arama" class="form-control" 
                               placeholder="Ürün adı veya barkod ile ara..." 
                               value="<?php echo htmlspecialchars($arama); ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Ara
                        </button>
                        <a href="urunleri_listele.php" class="btn btn-secondary">Temizle</a>
                    </div>
                </div>
            </form>

            <!-- Ürünler Tablosu -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ürün Adı</th>
                            <th>Barkod</th>
                            <th>Kategori</th>
                            <th>Miktar</th>
                            <th>Alış Fiyatı</th>
                            <th>Satış Fiyatı</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($sonuc) > 0): ?>
                            <?php while ($urun = mysqli_fetch_assoc($sonuc)): ?>
                                <tr>
                                    <td><?php echo $urun['id']; ?></td>
                                    <td><?php echo htmlspecialchars($urun['urun_adi']); ?></td>
                                    <td><?php echo htmlspecialchars($urun['barkod']); ?></td>
                                    <td><?php echo htmlspecialchars($urun['kategori_adi']); ?></td>
                                    <td><?php echo $urun['miktar']; ?></td>
                                    <td><?php echo number_format($urun['alis_fiyati'], 2); ?> ₺</td>
                                    <td><?php echo number_format($urun['satis_fiyati'], 2); ?> ₺</td>
                                    <td>
                                        <a href="urun_duzenle.php?id=<?php echo $urun['id']; ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="urun_sil.php?id=<?php echo $urun['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Bu ürünü silmek istediğinizden emin misiniz?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Ürün bulunamadı.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

