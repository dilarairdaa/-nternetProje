<?php 
session_start();
if (!isset($_SESSION['giris_yapti']) || $_SESSION['giris_yapti'] !== true) {
    header("Location: login.php");
    exit;
}

include("db.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: liste.php");
    exit;
}

$id = intval($_GET['id']);

$sorgu = "DELETE FROM students WHERE ID=$id";

if($baglanti->query($sorgu)){   
    header("Location: liste.php?durum=silindi");
    exit;
} else {  
    echo "Silme hatası: " . $baglanti->error;
}
?>