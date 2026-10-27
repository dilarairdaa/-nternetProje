<?php 
session_start();
// Eğer giriş yapılmadıysa kullanıcıyı hemen login.php sayfasına fırlat
if (!isset($_SESSION['giris_yapti']) || $_SESSION['giris_yapti'] !== true) {
    header("Location: login.php");
    exit;
}
include("db.php"); 
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Öğrenci Yönetim Paneli</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<a href="index.php" class="btn-cancel" style="position: absolute; left: 50px; top: 55px; font-size: 15px; padding: 10px;">← Anasayfaya Dön</a>

<div class="app-container">
    <div class="header-section">
        <h1>Öğrenci Yönetim Sistemi</h1>
        <a href="ekle.php" class="btn-primary">+ Yeni Kayıt</a>
    </div>

    <?php
    $sorgu = $baglanti->query("SELECT COUNT(*) as toplam FROM students");
    $sonuc = $sorgu->fetch_assoc();
    
    $bolum_sorgu = $baglanti->query("SELECT COUNT(DISTINCT bolum) as toplam_bolum FROM students");
    $bolum_sonuc = $bolum_sorgu->fetch_assoc();
    ?>

    <div class="stats-grid">
        <div class="stat-card">
            <span>Toplam Mevcut</span>
            <h2><?= $sonuc['toplam'] ?></h2>
        </div>
        <div class="stat-card">
            <span>Akademik Yıl</span>
            <h2 style="color: #0f172a;">2025-26</h2>
        </div>
        <div class="stat-card">
            <span>Kayıtlı Bölüm Çeşidi</span>
            <h2><?= $bolum_sonuc['toplam_bolum'] ?></h2>
        </div>
    </div>

    <div class="main-card">
        <div style="margin-bottom: 20px; display: flex; justify-content: flex-start;">
            <form method="GET" action="liste.php" style="display: flex; gap: 10px; width: 100%; max-width: 450px;">
                <input type="text" name="ara" placeholder="Öğrenci Numarası ile Ara..." style="flex: 1; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-size: 14px; color: #1e293b;" value="<?= isset($_GET['ara']) ? htmlspecialchars($_GET['ara']) : '' ?>">
                <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 14px;">Bul</button>
                <?php if(isset($_GET['ara']) && !empty($_GET['ara'])): ?>
                    <a href="liste.php" class="btn-cancel" style="padding: 10px 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: white;">Tüm Liste</a>
                <?php endif; ?>
            </form>
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>AD</th>
                    <th>SOYAD</th>
                    <th>NUMARA</th>
                    <th>BÖLÜM</th>
                    <th style="text-align: right;">İŞLEMLER</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_GET['ara']) && !empty(trim($_GET['ara']))) {
                    $aranan_numara = $baglanti->real_escape_string(trim($_GET['ara']));
                    $result = $baglanti->query("SELECT * FROM students WHERE ogrenci_no = '$aranan_numara'");
                } else {
                    $result = $baglanti->query("SELECT * FROM students");
                }
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                ?>
                <tr>
                    <td style="font-weight: 600;"><?= $row['ad'] ?></td>
                    <td style="font-weight: 600;"><?= $row['soyad'] ?></td>
                    <td><?= $row['ogrenci_no'] ?></td>
                    <td><span style="background: #f1f5f9; padding: 5px 10px; border-radius: 8px; font-size: 13px;"><?= $row['bolum'] ?></span></td>
                    <td class="action-links" style="text-align: right;">
                        <a href="guncelle.php?id=<?= $row['ID'] ?>" class="edit">Düzenle</a>
                        <a href="#" class="delete" onclick="openDeleteModal('sil.php?id=<?= $row['ID'] ?>'); return false;">Sil</a>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='5' style='text-align: center; padding: 30px; color: #64748b;'>Aradığınız numaraya ait öğrenci bulunamadı.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Custom Delete Modal -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <h3>Kaydı Sil</h3>
        <p>Bu öğrencinin kaydını tamamen silmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
        <div class="modal-actions">
            <button onclick="closeDeleteModal()" class="btn-modal-cancel">İptal</button>
            <a id="confirmDeleteBtn" href="#" class="btn-modal-confirm">Evet, Sil</a>
        </div>
    </div>
</div>

<script>
function openDeleteModal(deleteUrl) {
    document.getElementById('confirmDeleteBtn').href = deleteUrl;
    document.getElementById('deleteModal').classList.add('active');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
</script>

</body>
</html>