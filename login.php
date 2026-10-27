<?php
session_start();
include("db.php");

$hata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullanici_ad = $_POST['kullanici_ad'];
    $sifre = $_POST['sifre'];

    // Güvenli kontrol için hazırlık
    $sorgu = $baglanti->prepare("SELECT * FROM kullanicilar WHERE kullanici_ad = ? AND sifre = ?");
    $sorgu->bind_param("ss", $kullanici_ad, $sifre);
    $sorgu->execute();
    $sonuc = $sorgu->get_result();

    if ($sonuc->num_rows > 0) {
        // Giriş başarılı, oturumu başlat
        $_SESSION['giris_yapti'] = true;
        $_SESSION['kullanici'] = $kullanici_ad;
        
        // Giriş yapınca gitmek istediğin sayfa (liste.php veya ekle.php)
        header("Location: liste.php");
        exit;
    } else {
        $hata = "Hatalı kullanıcı adı veya şifre!";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sistem Girişi | Kampüs OBS</title>
    <style>
        body {
            background: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-card h2 {
            margin-bottom: 25px;
            color: #0f172a;
        }
        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #475569;
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            box-sizing: border-box;
            font-size: 15px;
        }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-wrapper input {
            padding-right: 40px;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            cursor: pointer;
            user-select: none;
            display: flex;
            align-items: center;
            color: #64748b;
            transition: color 0.2s;
        }
        .btn-login {
            background: #0f172a;
            color: white;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-login:hover { background: #1e293b; }
        .error-msg {
            color: #ef4444;
            background: #fef2f2;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Yönetici Girişi</h2>
    
    <?php if(!empty($hata)): ?>
        <div class="error-msg"><?= $hata ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label>Kullanıcı Adı</label>
            <input type="text" name="kullanici_ad" required>
        </div>
        <div class="form-group">
            <label>Şifre</label>
            <div class="password-wrapper">
                <input type="password" name="sifre" id="sifreInput" required>
                <span class="toggle-password" id="togglePasswordBtn" title="Basılı tutarak göster">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </span>
            </div>
        </div>
        <button type="submit" class="btn-login">Giriş Yap</button>
    </form>
</div>

<script>
    var passInput = document.getElementById("sifreInput");
    var toggleBtn = document.getElementById("togglePasswordBtn");

    function showPassword() {
        passInput.type = "text";
        toggleBtn.style.color = "#0f172a"; // İkona koyu renk (aktif)
    }

    function hidePassword() {
        passInput.type = "password";
        toggleBtn.style.color = "#64748b"; // İkona gri renk (pasif)
    }

    // Fare ile basılı tutulduğunda
    toggleBtn.addEventListener('mousedown', showPassword);
    toggleBtn.addEventListener('mouseup', hidePassword);
    toggleBtn.addEventListener('mouseleave', hidePassword); // Dışarı kaydırılırsa gizle
    
    // Telefon ve tabletler (dokunmatik) için
    toggleBtn.addEventListener('touchstart', function(e) {
        e.preventDefault(); // Varsayılan dokunma olayını engelle
        showPassword();
    });
    toggleBtn.addEventListener('touchend', hidePassword);
</script>
</body>
</html>