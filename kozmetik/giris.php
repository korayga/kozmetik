<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Zaten giriş yapmışsa dashboard'a yönlendir
if (isset($_SESSION['kullanici_id'])) {
    header('Location: dashboard.php');
}

$hata_mesaji = "";

// Form gönderildi mi?
if ($_POST) {
    $kullanici_adi = trim($_POST['kullanici_adi']);
    $sifre = $_POST['sifre'];
    
    if (empty($kullanici_adi) || empty($sifre)) {
        $hata_mesaji = "Kullanıcı adı ve şifre giriniz!";
    } else {
        // Giriş kontrolü
        if (kullaniciGiris($baglanti, $kullanici_adi, $sifre)) {
            // Son giriş tarihini güncelle
            $kullanici_id = $_SESSION['kullanici_id'];
            $tarih = date('Y-m-d H:i:s');
            mysqli_query($baglanti, "UPDATE kullanicilar SET son_giris = '$tarih' WHERE id = '$kullanici_id'");
            
           header('Location: dashboard.php');
        } else {
            $hata_mesaji = "Kullanıcı adı veya şifre hatalı!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş - <?php echo SITE_NAME; ?></title>
    
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
        .demo-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-primary mb-0">
                            <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
                        </h3>
                        <p class="text-muted mt-2"><?php echo SITE_NAME; ?></p>
                    </div>
                    <div class="card-body p-4">
                       
                        
                        <?php if ($hata_mesaji): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $hata_mesaji; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="kullanici_adi" class="form-label">Kullanıcı Adı</label>
                                <input type="text" class="form-control" id="kullanici_adi" name="kullanici_adi" 
                                       value="<?php echo isset($_POST['kullanici_adi']) ? guvenliYazi($_POST['kullanici_adi']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="sifre" class="form-label">Şifre</label>
                                <input type="password" class="form-control" id="sifre" name="sifre" required>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-0">Hesabınız yok mu? 
                                <a href="kayit.php" class="text-primary">Kayıt Olun</a>
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