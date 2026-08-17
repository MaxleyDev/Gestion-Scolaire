<?php
require_once 'includes/config.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: connexion.php');
    exit;
}

$id = $_GET['id'] ?? 0;
if ($id) {
    $stmt = $pdo->prepare("UPDATE students SET status = IF(status='active','inactive','active') WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: etudiants.php');
exit;