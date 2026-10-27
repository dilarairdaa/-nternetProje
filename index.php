<?php
include("db.php");
$sorgu = $baglanti->query("SELECT COUNT(*) as toplam FROM students");
$sonuc = $sorgu->fetch_assoc();
$ogrenci_sayisi = $sonuc['toplam'] ? $sonuc['toplam'] : 0;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampüs Bilgi Sistemi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Genel Reset ve Font Kurulumu */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 0 !important;
            margin: 0 !important;
        }

        .hero-bg {
            background: url('okul.png') no-repeat center center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* --------------------------------------------------
           YENİ ÜST MENÜ (NAVBAR) & AÇILIR MENÜLER (DROPDOWN)
        ----------------------------------------------------- */
        .main-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-container {
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            height: 80px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #0f172a;
            font-weight: 800;
            font-size: 20px;
        }

        .brand-logo .logo-icon {
            font-size: 28px;
            background: #f1f5f9;
            padding: 8px;
            border-radius: 12px;
        }

        /* Navigasyon Link Listesi */
        .nav-menu {
            display: flex;
            list-style: none;
            height: 100%;
        }

        .nav-item {
            position: relative;
            display: flex;
            align-items: center;
            height: 100%;
        }

        .nav-link {
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            font-size: 15px;
            padding: 0 18px;
            height: 100%;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .nav-link::after {
            content: ' ▾';
            font-size: 12px;
            margin-left: 5px;
            color: #94a3b8;
        }

        /* Sağdaki Özel Eylem Butonu (Örn: Hızlı Bilgi Sistemi) */
        .btn-action-nav {
            background: #3b82f6;
            color: white !important;
            border-radius: 50px;
            height: 42px !important;
            padding: 0 20px !important;
            align-self: center;
            margin-left: 10px;
        }
        .btn-action-nav::after { content: '' !important; } /* Ok işaretini kaldır */
        .btn-action-nav:hover { background: #2563eb !important; }

        /* Açılır Menü Penceresi (Dropdown) */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 240px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-top: 3px solid #3b82f6;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            list-style: none;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.25s ease;
        }

        .dropdown-menu li a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #334155;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s ease, color 0.2s ease;
        }

        /* Hover Etkileşimleri */
        .nav-item:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .nav-item:hover .nav-link {
            color: #3b82f6;
            background: #f8fafc;
        }

        .dropdown-menu li a:hover {
            background: #f1f5f9;
            color: #0f172a;
            padding-left: 25px; /* Hafif sağa kayma efekti */
        }

        /* --------------------------------------------------
           ANA İÇERİK ALANI (HERO & KARTLAR)
        ----------------------------------------------------- */
        .app-container {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
            flex: 1;
        }

        .hero-section {
            text-align: center;
            background: white;
            padding: 60px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 40px;
        }

        .hero-title {
            font-size: 42px;
            font-weight: 800;
            background: linear-gradient(to right, #0f172a, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            margin-top: 0;
        }

        .hero-subtitle {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 40px;
            line-height: 1.6;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-large {
            background: #0f172a;
            color: white;
            padding: 16px 40px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        .btn-large:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -3px rgba(0,0,0,0.15);
            background: #1e293b;
        }

        .icon {
            font-size: 54px;
            margin-bottom: 20px;
        }

        .stats-banner {
            background: rgba(15, 23, 42, 0.35); /* Koyu ama daha şeffaf bir renk */
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 50px 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 0;
            color: white;
            text-align: center;
            width: 100%;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stat-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.9);
            color: #0f172a;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
            font-size: 34px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .stat-number {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.6);
        }

        .stat-label {
            font-size: 16px;
            color: #f8fafc;
            font-weight: 500;
            text-shadow: 0 2px 4px rgba(0,0,0,0.6);
        }

        @media (max-width: 900px) {
            .stats-banner { grid-template-columns: repeat(2, 1fr); gap: 40px 20px; }
        }
        @media (max-width: 500px) {
            .stats-banner { grid-template-columns: 1fr; }
        }

        footer {
            background: #0f172a;
            color: #94a3b8;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            margin-top: auto;
        }
    </style>
</head>
<body>

<div class="hero-bg">
<header class="main-header">
    <nav class="nav-container">
        <a href="#" class="brand-logo">
            <span class="logo-icon">🎓</span>
            <span>KAMPÜS OBS</span>
        </a>

        <ul class="nav-menu">
            <li class="nav-item">
                <a class="nav-link">Öğrenci İşleri</a>
                <ul class="dropdown-menu">
                    <li><a href="login.php">Yeni Öğrenci Kayıt Et</a></li>
                    <li><a href="#">Ders Seçim İşlemleri</a></li>
                    <li><a href="#">Harç ve Ödemeler</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link">Akademik</a>
                <ul class="dropdown-menu">
                    <li><a href="#">Fakülteler & Bölümler</a></li>
                    <li><a href="#">Haftalık Ders Programı</a></li>
                    <li><a href="#">Sınav Takvimi</a></li>
                    <li><a href="#">Akademik Takvim</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link">Yönetim & Giriş</a>
                <ul class="dropdown-menu">
                    <li><a href="#">Öğretmen Giriş Paneli</a></li>
                    <li><a href="#">Yönetici / Personel Girişi</a></li>
                    <li><a href="#">Bakanlık / YÖK Entegrasyonu</a></li>
                    <li><a href="#">Sistem Ayarları</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link">İletişim</a>
                <ul class="dropdown-menu">
                    <li><a href="#">Kampüs Ulaşım Kılavuzu</a></li>
                    <li><a href="#">İletişim Formu</a></li>
                    <li><a href="#">Sıkça Sorulan Sorular</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link btn-action-nav">Hızlı Sistem Girişi</a>
            </li>
        </ul>
    </nav>
</header>

<!-- Fotoğrafın boşluğunu sağlamak ve sayfayı uzatmak için spacer -->
<div style="flex: 1; min-height: 75vh;"></div>

<div class="stats-banner">
    <div class="stat-item">
        <div class="stat-icon">🏛️</div>
        <div class="stat-number">266318</div>
        <div class="stat-label">Kütüphane Kaynak Sayısı</div>
    </div>
    <div class="stat-item">
        <div class="stat-icon">👥</div>
        <div class="stat-number"><?= $ogrenci_sayisi ?></div>
        <div class="stat-label">Öğrenci Sayısı</div>
    </div>
    <div class="stat-item">
        <div class="stat-icon">🎓</div>
        <div class="stat-number">3436</div>
        <div class="stat-label">Akademik Personel Sayısı</div>
    </div>
    <div class="stat-item">
        <div class="stat-icon">🔬</div>
        <div class="stat-number">9084</div>
        <div class="stat-label">Bilimsel Etkinlik Sayısı<br>2017-2020</div>
    </div>
</div>
</div>

<footer>
    &copy; 2026 Kampüs Bilgi Sistemi - Tüm Hakları Saklıdır.
</footer>

</body>
</html>