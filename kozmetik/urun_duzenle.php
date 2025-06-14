<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

girisKontrol();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    $_SESSION['hata_mesaji'] = 'Geçersiz ürün ID!';
    header('Location: urunleri_listele.php');
    exit;
}

// Ürün bilgilerini getir
$sql = "SELECT * FROM urunler WHERE id = $id AND aktif = 1";
$sonuc = mysqli_query($baglanti, $sql);
$urun = mysqli_fetch_assoc($sonuc);

if (!$urun) {
    $_SESSION['hata_mesaji'] = 'Ürün bulunamadı!';
    header('Location: urunleri_listele.php');
    exit;
}

// Kategorileri getir
$kategoriler_sql = "SELECT * FROM kategoriler ORDER BY kategori_adi";
$kategoriler = mysqli_query($baglanti, $kategoriler_sql);

$hata = '';

// Form gönderildi mi?
if ($_POST) {
    $urun_adi = trim($_POST['urun_adi']);
    $barkod = trim($_POST['barkod']);
    $kategori_id = (int)$_POST['kategori_id'];
    $miktar = (int)$_POST['miktar'];
    $alis_fiyati = (float)str_replace(',', '.', $_POST['alis_fiyati']);
    $satis_fiyati = (float)str_replace(',', '.', $_POST['satis_fiyati']);
    
    // Basit kontroller
    if (empty($urun_adi)) {
        $hata = 'Ürün adı boş olamaz!';
    } elseif ($kategori_id <= 0) {
        $hata = 'Kategori seçiniz!';
    } elseif ($miktar < 0) {
        $hata = 'Miktar negatif olamaz!';
    } elseif ($alis_fiyati < 0 || $satis_fiyati < 0) {
        $hata = 'Fiyatlar negatif olamaz!';
    } else {
        // Güncelleme
        $update_sql = "UPDATE urunler SET 
                       urun_adi = '$urun_adi',
                       barkod = '$barkod',
                       kategori_id = $kategori_id,
                       miktar = $miktar,
                       alis_fiyati = $alis_fiyati,
                       satis_fiyati = $satis_fiyati
                       WHERE id = $id";
        
        if (mysqli_query($baglanti, $update_sql)) {
            $_SESSION['basari_mesaji'] = 'Ürün başarıyla güncellendi!';
            header('Location: urunleri_listele.php');
            exit;
        } else {
            $hata = 'Güncelleme hatası: ' . mysqli_error($baglanti);
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-edit"></i> Ürün Düzenle</h5>
            <a href="urunleri_listele.php" class="btn btn-secondary btn-sm float-end">
                <i class="fas fa-arrow-left"></i> Geri
            </a>
        </div>
        <div class="card-body">
            <?php if ($hata): ?>
                <div class="alert alert-danger"><?php echo $hata; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Ürün Adı *</label>
                        <input type="text" name="urun_adi" class="form-control" 
                               value="<?php echo htmlspecialchars($urun['urun_adi']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Barkod</label>
                        <input type="text" name="barkod" class="form-control" 
                               value="<?php echo htmlspecialchars($urun['barkod']); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Kategori *</label>
                        <select name="kategori_id" class="form-control" required>
                            <option value="">Seçiniz...</option>
                            <?php mysqli_data_seek($kategoriler, 0); ?>
                            <?php while ($kategori = mysqli_fetch_assoc($kategoriler)): ?>
                                <option value="<?php echo $kategori['id']; ?>" 
                                        <?php echo ($urun['kategori_id'] == $kategori['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($kategori['kategori_adi']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Miktar *</label>
                        <input type="number" name="miktar" class="form-control" 
                               value="<?php echo $urun['miktar']; ?>" min="0" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Alış Fiyatı (₺) *</label>
                        <input type="text" name="alis_fiyati" class="form-control" 
                               value="<?php echo number_format($urun['alis_fiyati'], 2); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Satış Fiyatı (₺) *</label>
                        <input type="text" name="satis_fiyati" class="form-control" 
                               value="<?php echo number_format($urun['satis_fiyati'], 2); ?>" required>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

