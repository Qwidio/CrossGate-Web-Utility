<?php
require_once 'processes/database.php';
$pdo = new PDO("mysql:host=$hosts;dbname=$dbase", $names, $passw);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $pdo->query("SELECT bannerRefImg, refLinks FROM banners WHERE bannerState = 'active' ORDER BY bannerDates DESC");
$slider = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($slider);