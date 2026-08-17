<?php
require_once 'includes/config.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$date = $_GET['date'] ?? date('Y-m-d');

// Fetch payments for that date
$stmt = $pdo->prepare("SELECT payments.*, students.lastname, students.firstname, students.matricule 
                        FROM payments 
                        JOIN students ON payments.student_id = students.id 
                        WHERE payment_date = ? 
                        ORDER BY students.lastname");
$stmt->execute([$date]);
$payments = $stmt->fetchAll();

// Also get total sum
$sumStmt = $pdo->prepare("SELECT SUM(amount) as total FROM payments WHERE payment_date = ?");
$sumStmt->execute([$date]);
$total = $sumStmt->fetch()['total'] ?? 0;

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=rapport_' . $date . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Date', 'Nom', 'Prénom', 'Matricule', 'Montant', 'Type']);

foreach ($payments as $p) {
    fputcsv($output, [
        $p['payment_date'],
        $p['lastname'],
        $p['firstname'],
        $p['matricule'],
        $p['amount'],
        $p['payment_type']
    ]);
}

// Add total row
fputcsv($output, ['', '', '', 'TOTAL', $total, '']);

fclose($output);
exit;