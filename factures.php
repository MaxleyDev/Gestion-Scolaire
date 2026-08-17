<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$sql = "SELECT invoices.*, students.lastname, students.firstname, students.matricule 
        FROM invoices 
        JOIN students ON invoices.student_id = students.id 
        ORDER BY invoices.issue_date DESC";
$factures = $pdo->query($sql)->fetchAll();
?>

<h2>Factures / Reçus</h2>
<div class="table-actions">
    <a href="ajouter_facture.php" class="btn btn-primary">Créer une facture</a>
    <a href="exporter_factures.php" class="btn btn-primary" style="background: #28a745;"><i class="fa fa-file-excel"></i> Exporter CSV</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>N° Facture</th>
            <th>Élève</th>
            <th>Montant</th>
            <th>Type</th>
            <th>Date d'émission</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($factures as $f): ?>
        <tr>
            <td><?= htmlspecialchars($f['invoice_no']) ?></td>
            <td><?= htmlspecialchars($f['firstname'] . ' ' . $f['lastname']) ?><br><small><?= $f['matricule'] ?></small></td>
            <td><?= number_format($f['amount'], 0, ',', ' ') ?> Gourdes</td>
            <td><?= htmlspecialchars($f['payment_type']) ?></td>
            <td><?= htmlspecialchars($f['issue_date']) ?></td>
            <td>
                <span class="status-badge status-<?= $f['status'] ?>">
                    <?= $f['status'] == 'paid' ? 'Payée' : 'En attente' ?>
                </span>
            </td>
            <td>
                <a href="imprimer_facture.php?id=<?= $f['id'] ?>" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>