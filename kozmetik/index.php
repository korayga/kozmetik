<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Oturum açıksa dashboard'a, değilse giriş sayfasına yönlendir
if (isset($_SESSION['kullanici_id'])) {
    header('Location: dashboard.php');
} else {
    header('Location: giris.php');
}
exit;
?>