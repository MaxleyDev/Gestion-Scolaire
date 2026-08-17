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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture <?= $facture['invoice_no'] ?></title>
    <style>
        body { font-family: 'Inter', sans-serif; margin: 40px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .school-name { font-size: 24px; font-weight: bold; }
        .invoice-title { font-size: 28px; color: #667eea; margin: 20px 0; }
        .details { margin: 20px 0; }
        .details table { width: 100%; border-collapse: collapse; }
        .details td { padding: 8px; }
        .footer { margin-top: 40px; text-align: right; }
        .print-btn { margin-top: 20px; text-align: center; }
        .btn-success {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            margin: 0 5px;
        }
        .btn-success:hover {
            background: #218838;
        }
        @media print {
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="school-name">École Supérieure</div>
            <div></div>
        </div>
        <div class="invoice-title">FACTURE / REÇU</div>
        <div><strong>N° Facture:</strong> <?= $facture['invoice_no'] ?></div>
        <div><strong>Date d'émission:</strong> <?= $facture['issue_date'] ?></div>
        <hr>
        <div class="details">
            <table>
                <tr><td><strong>Élève:</strong></td><td><?= htmlspecialchars($facture['firstname'] . ' ' . $facture['lastname']) ?></td></tr>
                <tr><td><strong>Matricule:</strong></td><td><?= $facture['matricule'] ?></td></tr>
                <tr><td><strong>Adresse:</strong></td><td><?= htmlspecialchars($facture['address']) ?></td></tr>
                <tr><td><strong>Téléphone:</strong></td><td><?= htmlspecialchars($facture['phone']) ?></td></tr>
                <tr><td><strong>Montant:</strong></td><td><?= number_format($facture['amount'], 0, ',', ' ') ?> Gourdes</td></tr>
                <tr><td><strong>Type de paiement:</strong></td><td><?= htmlspecialchars($facture['payment_type']) ?></td></tr>
                <tr><td><strong>Statut:</strong></td><td><?= $facture['status'] == 'paid' ? 'Payée' : 'En attente' ?></td></tr>
            </table>
        </div>
        <div class="footer">
            <p>Merci de votre confiance.</p>
        </div>
    </div>
    <div class="print-btn">
        <button onclick="window.print()" class="btn btn-primary">Imprimer</button>
        <a href="exporter_facture.php?id=<?= $facture['id'] ?>" class="btn btn-success">Exporter CSV</a>
        <a href="factures.php" class="btn btn-secondary">Retour</a>
    </div>
</body>
</html>