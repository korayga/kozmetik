<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$hata_mesaji = "";
$basari_mesaji = "";

// Form gönderildi mi?
if ($_POST) {
    $kullanici_adi = trim($_POST['kullanici_adi']);
    $sifre = $_POST['sifre'];
    $sifre_tekrar = $_POST['sifre_tekrar'];
    $tam_isim = trim($_POST['tam_isim']);
    $email = trim($_POST['email']);
    
    // Basit doğrulama
    if (empty($kullanici_adi) || empty($sifre) || empty($tam_isim) || empty($email)) {
        $hata_mesaji = "Tüm alanları doldurunuz!";
    } elseif ($sifre != $sifre_tekrar) {
        $hata_mesaji = "Şifreler eşleşmiyor!";
    } elseif (strlen($sifre) < 4) {
        $hata_mesaji = "Şifre en az 4 karakter olmalıdır!";
    } else {
        // Kayıt işlemi
        if (kullaniciKayit($baglanti, $kullanici_adi, $sifre, $tam_isim, $email)) {
            $basari_mesaji = "Kayıt başarılı! Giriş ";
            header("Location: giris.php?kayit=ok");
        } else {
            $hata_mesaji = "Bu kullanıcı adı veya Email zaten kullanılmaktadır!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - <?php echo SITE_NAME; ?></title>
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .card-header {
            background: transparent;
            border-bottom: none;
            text-align: center;
            padding: 2rem 2rem 0;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-primary mb-0">
                            <i class="fas fa-user-plus me-2"></i>Kayıt Ol
                        </h3>
                        <p class="text-muted mt-2"><?php echo SITE_NAME; ?></p>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($hata_mesaji): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $hata_mesaji; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($basari_mesaji): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i><?php echo $basari_mesaji; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="tam_isim" class="form-label">İsim Soyisim</label>
                                <input type="text" class="form-control" id="tam_isim" name="tam_isim" 
                                       value="<?php echo isset($_POST['tam_isim']) ? guvenliYazi($_POST['tam_isim']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="kullanici_adi" class="form-label">Kullanıcı Adı</label>
                                <input type="text" class="form-control" id="kullanici_adi" name="kullanici_adi" 
                                       value="<?php echo isset($_POST['kullanici_adi']) ? guvenliYazi($_POST['kullanici_adi']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">E-posta</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo isset($_POST['email']) ? guvenliYazi($_POST['email']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="sifre" class="form-label">Şifre</label>
                                <input type="password" class="form-control" id="sifre" name="sifre" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="sifre_tekrar" class="form-label">Şifre Tekrar</label>
                                <input type="password" class="form-control" id="sifre_tekrar" name="sifre_tekrar" required>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Kayıt Ol
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-0">Zaten hesabınız var mı? 
                                <a href="giris.php" class="text-primary">Giriş Yapın</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>