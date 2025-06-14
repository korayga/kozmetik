<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
girisKontrol();
if (!isset($_GET['id'])) {
    header("Location: urunleri_listele.php");
    exit();
}
$urun_id = (int)$_GET['id'];
$stmt = mysqli_prepare($baglanti, "DELETE FROM urunler WHERE `urunler`.`id` = ?");
mysqli_stmt_bind_param($stmt, "i", $urun_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
header("Location: urunleri_listele.php");
exit();
?>