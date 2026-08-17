<?php
require_once 'includes/config.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: connexion.php');
    exit;
}

$id = $_GET['id'] ?? 0;
if (!$id) {
    header('Location: factures.php');
    exit;
}

$stmt = $pdo->prepare("SELECT invoices.*, students.lastname, students.firstname, students.matricule, students.address, students.phone 
                        FROM invoices 
                        JOIN students ON invoices.student_id = students.id 
                        WHERE invoices.id = ?");
$stmt->execute([$id]);
$facture = $stmt->fetch();
if (!$facture) {
    header('Location: factures.php');
    exit;
}

// En-têtes pour téléchargement CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="facture_' . $facture['invoice_no'] . '.csv"');

// Flux de sortie
$sortie = fopen('php://output', 'w');

// BOM UTF-8 pour Excel
fprintf($sortie, chr(0xEF).chr(0xBB).chr(0xBF));

// En-têtes
fputcsv($sortie, ['Champ', 'Valeur']);

// Lignes de données
fputcsv($sortie, ['N° Facture', $facture['invoice_no']]);
fputcsv($sortie, ['Date d\'émission', $facture['issue_date']]);
fputcsv($sortie, ['Élève', $facture['firstname'] . ' ' . $facture['lastname']]);
fputcsv($sortie, ['Matricule', $facture['matricule']]);
fputcsv($sortie, ['Adresse', $facture['address']]);
fputcsv($sortie, ['Téléphone', $facture['phone']]);
fputcsv($sortie, ['Montant', number_format($facture['amount'], 0, ',', ' ') . ' Gourdes']);
fputcsv($sortie, ['Type de paiement', $facture['payment_type']]);
fputcsv($sortie, ['Statut', $facture['status'] == 'paid' ? 'Payée' : 'En attente']);

fclose($sortie);
exit;