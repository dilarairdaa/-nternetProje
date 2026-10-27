<?php
$baglanti = new mysqli("localhost", "root", "", "okul");

if ($baglanti->connect_error) {
    die("Bağlantı hatası: " . $baglanti->connect_error);
}
?>