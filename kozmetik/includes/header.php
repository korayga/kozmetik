<?php
// Gerekli dosyaları dahil et
require_once 'includes/db.php';       // Veritabanı bağlantısı ve SITE_NAME
require_once 'includes/functions.php'; // Yardımcı fonksiyonlar (guvenliYazi vb.)
require_once 'includes/auth.php';      // Kimlik doğrulama fonksiyonları (girisKontrol, oturumKontrol, cikisYap vb.)

girisKontrol();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
       

        /* Genel Body Stili */
        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed; /* Arka planın kaymasını engelle */
            overflow-x: hidden; /* Yatay kaydırmayı engelle */
        }

        /* Navbar Stili */
        .navbar {
            background-color: #ffffff; 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); 
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color); 
        }

    

        /* Ana İçerik Alanı (main) */
        main {
            flex-grow: 1; 
            padding-top: 2rem; 
            padding-bottom: 2rem; 
        }


        /* Genel Kart Stilleri (Listeler ve formlar için) */
        .card {
    
            margin-bottom: 1.5rem;
            overflow: hidden; /* İçerik taşmasını engelleme */
        }
       
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--dark-text);
            padding: 1rem 1.5rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header h5 {
            margin-bottom: 0;
            font-size: 1.25rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .table {
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 0;
        }
        .table thead {
            background-color: var(--light-bg);
            border-bottom: 1px solid var(--border-color);
        }
        .table th, .table td {
            padding: 1.2rem 1.5rem;
            vertical-align: middle;
            border-top: none;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Diğer Genel Bileşen Stilleri */
        .badge {
            font-size: 0.85em;
            padding: 0.5em 0.7em;
            border-radius: 0.35rem;
            font-weight: 600;
        }

        .animate-fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
    

    </style>
</head>
<body>

<?php if (isset($_SESSION['kullanici_id'])): ?>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">
            <i class="fas fa-cubes"></i> <?php echo SITE_NAME; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                        <i class="fas fa-home me-1"></i>Ana Sayfa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'urunleri_listele.php' || basename($_SERVER['PHP_SELF']) == 'urun_ekle.php' || basename($_SERVER['PHP_SELF']) == 'urun_duzenle.php' || basename($_SERVER['PHP_SELF']) == 'urun_sil.php') ? 'active' : ''; ?>" 
                       href="urunleri_listele.php">
                        <i class="fas fa-boxes me-1"></i>Ürünler
                    </a>
                </li>
             
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'raporlar.php') ? 'active' : ''; ?>" href="raporlar.php">
                        <i class="fas fa-chart-bar me-1"></i>Raporlar
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="kullaniciDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        <?php echo guvenliYazi(isset($_SESSION['tam_isim']) ? $_SESSION['tam_isim'] : $_SESSION['kullanici_adi']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="kullaniciDropdown">
                        <li><a class="dropdown-item text-danger" href="cikis.php"><i class="fas fa-sign-out-alt me-2"></i>Çıkış Yap</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php endif; ?>

<main class="container-fluid">
