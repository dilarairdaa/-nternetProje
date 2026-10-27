<?php 
session_start();
// Giriş oturumu yoksa veya true değilse kullanıcıyı login.php'ye fırlat
if (!isset($_SESSION['giris_yapti']) || $_SESSION['giris_yapti'] !== true) {
    header("Location: login.php");
    exit;
}

include("db.php"); 

// URL'den ID gelmediyse veya boşsa doğrudan listeye geri gönder
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: liste.php");
    exit;
}

$id = intval($_GET['id']); // SQL güvenliği için gelen ID değerini sayıya zorla
$sorgu = $baglanti->query("SELECT * FROM students WHERE ID=$id");
$data = $sorgu->fetch_assoc();

// Veritabanında bu ID'ye ait bir öğrenci bulunamadıysa listeye geri gönder
if (!$data) {
    header("Location: liste.php");
    exit;
}

if($_POST){
    $ad = $baglanti->real_escape_string($_POST['ad']);
    $soyad = $baglanti->real_escape_string($_POST['soyad']);
    $numara = $baglanti->real_escape_string($_POST['numara']);
    $bolum = $baglanti->real_escape_string($_POST['bolum']);
    if($bolum == 'Diğer' && isset($_POST['diger_bolum'])) {
        $bolum = $baglanti->real_escape_string($_POST['diger_bolum']);
    }

    $guncelle_sorgu = "UPDATE students SET ad='$ad', soyad='$soyad', ogrenci_no='$numara', bolum='$bolum' WHERE ID=$id";
    if($baglanti->query($guncelle_sorgu)){
        header("Location: liste.php");
        exit;
    }
}

$predefined_bolumler = ['Bilgisayar Programcılığı', 'Makine', 'Makine Mühendisliği', 'Laboratuvar Teknolojisi'];
$is_other = !in_array($data['bolum'], $predefined_bolumler) && !empty($data['bolum']);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Öğrenci Bilgi Güncelleme</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="app-container">
    <div class="header-section">
        <h1>Öğrenci Bilgi Güncelleme</h1>
    </div>
    <div class="main-card form-card">
        <form method="POST" autocomplete="off">
            <div class="input-group">
                <label>Ad</label>
                <input type="text" name="ad" value="<?= htmlspecialchars($data['ad']) ?>" required onchange="validateText(this)">
            </div>
            <div class="input-group">
                <label>Soyad</label>
                <input type="text" name="soyad" value="<?= htmlspecialchars($data['soyad']) ?>" required onchange="validateText(this)">
            </div>
            <div class="input-group">
                <label>Öğrenci Numarası</label>
                <input type="text" name="numara" value="<?= htmlspecialchars($data['ogrenci_no']) ?>" required onchange="validateNumber(this)">
            </div>
            <div class="input-group">
                <label>Bölüm</label>
                <select name="bolum" required onchange="checkBolum(this)" style="width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 15px; color: #1e293b; outline: none; transition: all 0.3s ease; appearance: none; background: #f8fafc url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"%2364748b\" viewBox=\"0 0 16 16\"><path d=\"M8 11.5l-5-5h10z\"/></svg>') no-repeat right 16px center; background-size: 12px;">
                    <option value="" disabled>Bölüm Seçiniz</option>
                    <option value="Bilgisayar Programcılığı" <?= $data['bolum'] == 'Bilgisayar Programcılığı' ? 'selected' : '' ?>>Bilgisayar Programcılığı</option>
                    <option value="Makine" <?= $data['bolum'] == 'Makine' ? 'selected' : '' ?>>Makine</option>
                    <option value="Makine Mühendisliği" <?= $data['bolum'] == 'Makine Mühendisliği' ? 'selected' : '' ?>>Makine Mühendisliği</option>
                    <option value="Laboratuvar Teknolojisi" <?= $data['bolum'] == 'Laboratuvar Teknolojisi' ? 'selected' : '' ?>>Laboratuvar Teknolojisi</option>
                    <option value="Diğer" <?= $is_other ? 'selected' : '' ?>>Diğer (Belirtiniz)</option>
                </select>
                <input type="text" name="diger_bolum" id="diger_bolum_input" placeholder="Lütfen bölüm adını giriniz" style="display: <?= $is_other ? 'block' : 'none' ?>; margin-top: 10px; width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 15px;" value="<?= $is_other ? htmlspecialchars($data['bolum']) : '' ?>" <?= $is_other ? 'required' : '' ?> onchange="validateText(this)">
            </div>
            <div class="button-group">
                <button type="submit" class="btn-save">Güncelle</button>
                <a href="liste.php" class="btn-cancel">Vazgeç ve Geri Dön</a>
            </div>
        </form>
    </div>
</div>
<script>
function checkBolum(select) {
    var digerInput = document.getElementById('diger_bolum_input');
    if (select.value === 'Diğer') {
        digerInput.style.display = 'block';
        digerInput.required = true;
    } else {
        digerInput.style.display = 'none';
        digerInput.required = false;
        digerInput.value = '';
    }
}
function validateText(input) {
    if (/[0-9]/.test(input.value)) {
        alert("Hata: Lütfen bu alana sadece metin (harf) giriniz! Sayı girişi yapılamaz.");
        input.value = input.value.replace(/[0-9]/g, '');
        setTimeout(() => input.focus(), 10);
    }
}
function validateNumber(input) {
    if (/[^0-9]/.test(input.value)) {
        alert("Hata: Lütfen numara alanına sadece sayı giriniz! Metin girişi yapılamaz.");
        input.value = input.value.replace(/[^0-9]/g, '');
        setTimeout(() => input.focus(), 10);
    }
}
</script>
</body>
</html>