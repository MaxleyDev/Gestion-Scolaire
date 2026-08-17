<?php
require_once 'includes/config.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: connexion.php');
    exit;
}

// Récupérer toutes les factures avec détails des élèves
$sql = "SELECT invoices.invoice_no, students.lastname, students.firstname, students.matricule, 
               invoices.amount, invoices.payment_type, invoices.issue_date, invoices.status
        FROM invoices 
        JOIN students ON invoices.student_id = students.id 
        ORDER BY invoices.issue_date DESC";
$factures = $pdo->query($sql)->fetchAll();

// En-têtes CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=factures_' . date('Y-m-d') . '.csv');

$sortie = fopen('php://output', 'w');

// En-têtes des colonnes
fputcsv($sortie, ['N° Facture', 'Nom', 'Prénom', 'Matricule', 'Montant', 'Type', 'Date d\'émission', 'Statut']);

// Lignes de données
foreach ($factures as $f) {
    fputcsv($sortie, [
        $f['invoice_no'],
        $f['lastname'],
        $f['firstname'],
        $f['matricule'],
        $f['amount'],
        $f['payment_type'],
        $f['issue_date'],
        $f['status'] == 'paid' ? 'Payée' : 'En attente'
    ]);
}

fclose($sortie);
exit;