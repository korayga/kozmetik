<?php

require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

girisKontrol();

// Kategorileri getir
$kategoriler = [];
$kategori_sorgu = mysqli_query($baglanti, "SELECT id, kategori_adi FROM kategoriler ORDER BY kategori_adi");
if ($kategori_sorgu) {
    while ($row = mysqli_fetch_assoc($kategori_sorgu)) {
        $kategoriler[] = $row;
    }
}

$hatalar = [];
$basari_mesaji = '';


if (isset($_POST['kaydet'])) { // Form gönderimi kontrol
    // Verileri al
    $urun_adi = mysqli_real_escape_string($baglanti, trim($_POST['urun_adi']));
    $barkod = mysqli_real_escape_string($baglanti, trim($_POST['barkod']));
    $kategori_id = (int)$_POST['kategori_id'];
    $marka = mysqli_real_escape_string($baglanti, trim($_POST['marka']));
    $model = mysqli_real_escape_string($baglanti, trim($_POST['model']));
    $renk = mysqli_real_escape_string($baglanti, trim($_POST['renk']));
    $miktar = (int)$_POST['miktar'];
    $min_stok = (int)$_POST['min_stok'];
    $alis_fiyati = (float)str_replace(',', '.', $_POST['alis_fiyati']);
    $satis_fiyati = (float)str_replace(',', '.', $_POST['satis_fiyati']);
    $notlar = mysqli_real_escape_string($baglanti, trim($_POST['notlar']));

    // Basit kontroller
    if (empty($urun_adi)) {
        $hatalar[] = "Ürün adı boş olamaz";
    }
    if ($kategori_id <= 0) {
        $hatalar[] = "Kategori seçmelisiniz";
    }

    // Barkod kontrolü
    if (!empty($barkod)) {
        $stmt = mysqli_prepare($baglanti, "SELECT id FROM urunler WHERE barkod = ?");
        mysqli_stmt_bind_param($stmt, "s", $barkod);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $hatalar[] = "Bu barkod zaten kullanılıyor";
        }
        mysqli_stmt_close($stmt);
    }

    // Hata yoksa kaydet
    if (empty($hatalar)) {
        $sql = "INSERT INTO urunler (urun_adi, kategori_id, miktar, alis_fiyati, satis_fiyati) 
                VALUES ('$urun_adi', $kategori_id, $miktar, $alis_fiyati, $satis_fiyati)";

        if (mysqli_query($baglanti, $sql)) {
            $basari_mesaji = "Ürün başarıyla eklendi!";
            // Formu temizle
            unset($_POST);
        } else {
            $hatalar[] = "Hata: " . mysqli_error($baglanti);
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Yeni Ürün Ekle</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Hatalar -->
                    <?php if (!empty($hatalar)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($hatalar as $hata): ?>
                                <div><?php echo $hata; ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Başarı -->
                    <?php if ($basari_mesaji): ?>
                        <div class="alert alert-success">
                            <?php echo $basari_mesaji; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label">Ürün Adı *</label>
                            <input type="text" name="urun_adi" class="form-control" 
                                   value="<?php echo isset($_POST['urun_adi']) ? $_POST['urun_adi'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Barkod</label>
                            <input type="text" name="barkod" class="form-control" 
                                   value="<?php echo isset($_POST['barkod']) ? $_POST['barkod'] : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori *</label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="">Seçin</option>
                                <?php foreach ($kategoriler as $kat): ?>
                                    <option value="<?php echo $kat['id']; ?>" 
                                            <?php echo (isset($_POST['kategori_id']) && $_POST['kategori_id'] == $kat['id']) ? 'selected' : ''; ?>>
                                        <?php echo $kat['kategori_adi']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Marka</label>
                                <input type="text" name="marka" class="form-control" 
                                       value="<?php echo isset($_POST['marka']) ? $_POST['marka'] : ''; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Model</label>
                                <input type="text" name="model" class="form-control" 
                                       value="<?php echo isset($_POST['model']) ? $_POST['model'] : ''; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Renk</label>
                                <input type="text" name="renk" class="form-control" 
                                       value="<?php echo isset($_POST['renk']) ? $_POST['renk'] : ''; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Miktar</label>
                                <input type="number" name="miktar" class="form-control" min="0"
                                       value="<?php echo isset($_POST['miktar']) ? $_POST['miktar'] : '0'; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Min. Stok</label>
                                <input type="number" name="min_stok" class="form-control" min="0"
                                       value="<?php echo isset($_POST['min_stok']) ? $_POST['min_stok'] : '5'; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Alış Fiyatı</label>
                                <input type="text" name="alis_fiyati" class="form-control" 
                                       value="<?php echo isset($_POST['alis_fiyati']) ? $_POST['alis_fiyati'] : '0.00'; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Satış Fiyatı</label>
                                <input type="text" name="satis_fiyati" class="form-control" 
                                       value="<?php echo isset($_POST['satis_fiyati']) ? $_POST['satis_fiyati'] : '0.00'; ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notlar</label>
                            <textarea name="notlar" class="form-control" rows="3"><?php echo isset($_POST['notlar']) ? $_POST['notlar'] : ''; ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="urunleri_listele.php" class="btn btn-secondary">Geri</a>
                            <button type="submit" name="kaydet" class="btn btn-primary">Kaydet</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>