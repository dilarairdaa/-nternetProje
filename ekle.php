<?php include("db.php"); ?> <!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Öğrenci Ekle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app-container">
    <div class="header-section">
        <h1>Yeni Öğrenci Ekle</h1>
        <a href="liste.php" class="btn-secondary" style="text-decoration:none; color:#64748b; font-weight:600; font-size:14px;">← Listeye Geri Dön</a>
    </div>

    <div class="main-card form-card">
        <form method="POST" autocomplete="off">
            <div class="input-group">
                <label>Öğrenci Bilgileri</label>
                <input type="text" name="ad" placeholder="Ad" required onchange="validateText(this)">
                <input type="text" name="soyad" placeholder="Soyad" required onchange="validateText(this)">
            </div>
            <div class="input-group">
                <label>Okul Detayları</label>
                <input type="text" name="numara" placeholder="Öğrenci Numarası" required onchange="validateNumber(this)">
                <select name="bolum" required onchange="checkBolum(this)" style="width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 15px; color: #1e293b; outline: none; transition: all 0.3s ease; appearance: none; background: #f8fafc url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"%2364748b\" viewBox=\"0 0 16 16\"><path d=\"M8 11.5l-5-5h10z\"/></svg>') no-repeat right 16px center; background-size: 12px;">
                    <option value="" disabled selected>Bölüm Seçiniz</option>
                    <option value="Bilgisayar Programcılığı">Bilgisayar Programcılığı</option>
                    <option value="Makine">Makine</option>
                    <option value="Makine Mühendisliği">Makine Mühendisliği</option>
                    <option value="Laboratuvar Teknolojisi">Laboratuvar Teknolojisi</option>
                    <option value="Diğer">Diğer (Belirtiniz)</option>
                </select>
                <input type="text" name="diger_bolum" id="diger_bolum_input" placeholder="Lütfen bölüm adını giriniz" style="display: none; margin-top: 10px; width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 15px;" onchange="validateText(this)">
            </div>
            <button type="submit">Sisteme Kaydet</button>
        </form>
    </div>
</div>

<?php
if($_POST){
    $ad = $_POST['ad']; 
    $soyad = $_POST['soyad']; 
    $numara = $_POST['numara'];
    $bolum = $_POST['bolum'];
    if($bolum == 'Diğer' && isset($_POST['diger_bolum'])) {
        $bolum = $_POST['diger_bolum'];
    }

    $sorgu = "INSERT INTO students (ad, soyad, ogrenci_no, bolum) 
              VALUES ('$ad', '$soyad', '$numara', '$bolum')";

    if($baglanti->query($sorgu)){
        header("Location: liste.php");
    } else {
        echo "Kayıt hatası: " . $baglanti->error;
    }
}
?>

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