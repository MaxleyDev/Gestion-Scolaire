<?php
require_once 'includes/config.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: connexion.php');
    exit;
}

$filtre_classe = $_GET['classe'] ?? '';
$filtre_date = $_GET['date'] ?? date('Y-m-d');

$sql = "SELECT attendance.session_date, students.lastname, students.firstname, students.matricule, attendance.class, attendance.status 
        FROM attendance 
        JOIN students ON attendance.student_id = students.id 
        WHERE 1=1";
$params = [];
if (!empty($filtre_classe)) {
    $sql .= " AND attendance.class = ?";
    $params[] = $filtre_classe;
}
if (!empty($filtre_date)) {
    $sql .= " AND attendance.session_date = ?";
    $params[] = $filtre_date;
}
$sql .= " ORDER BY attendance.session_date DESC, students.lastname";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$presences = $stmt->fetchAll();

// En-têtes pour téléchargement CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=presences_' . date('Ymd') . '.csv');

$sortie = fopen('php://output', 'w');
fputcsv($sortie, ['Date', 'Nom', 'Prénom', 'Matricule', 'Classe', 'Statut']);

foreach ($presences as $p) {
    fputcsv($sortie, [
        $p['session_date'],
        $p['lastname'],
        $p['firstname'],
        $p['matricule'],
        $p['class'],
        $p['status'] == 'present' ? 'Présent' : 'Absent'
    ]);
}
fclose($sortie);
exit;