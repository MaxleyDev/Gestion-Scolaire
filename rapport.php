<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$date_filtre = $_GET['date'] ?? date('Y-m-d');

// Totaux
$total_etudiants = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();

// Paiements à la date sélectionnée
$stmt = $pdo->prepare("SELECT SUM(amount) as total FROM payments WHERE payment_date = ?");
$stmt->execute([$date_filtre]);
$paiements_quotidiens = $stmt->fetch()['total'] ?? 0;

// Paiements du mois en cours
$stmt = $pdo->prepare("SELECT SUM(amount) as total FROM payments WHERE MONTH(payment_date) = ? AND YEAR(payment_date) = ?");
$stmt->execute([date('m'), date('Y')]);
$paiements_mensuels = $stmt->fetch()['total'] ?? 0;

// Total général des paiements
$total_paiements = $pdo->query("SELECT SUM(amount) FROM payments")->fetchColumn();

// Derniers paiements pour le tableau
$recents = $pdo->query("SELECT payments.*, students.lastname, students.firstname, students.matricule 
                        FROM payments 
                        JOIN students ON payments.student_id = students.id 
                        ORDER BY payments.payment_date DESC 
                        LIMIT 20")->fetchAll();

// Mapping des types de paiement
$types_paiement = [
    'registration' => 'Inscription',
    'monthly' => 'Mensualité',
    'installment' => 'Versement',
    'balance' => 'Solde'
];
?>

<h2>Rapports & Statistiques</h2>

<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap:20px; margin-bottom:30px;">
    <div class="stat-card" style="background:white; padding:20px; border-radius:15px; box-shadow:0 5px 10px rgba(0,0,0,0.05);">
        <h3>Total élèves</h3>
        <p style="font-size:2rem; font-weight:bold;"><?= $total_etudiants ?></p>
    </div>
    <div class="stat-card" style="background:white; padding:20px; border-radius:15px; box-shadow:0 5px 10px rgba(0,0,0,0.05);">
        <h3>Paiements aujourd'hui</h3>
        <p style="font-size:2rem; font-weight:bold;"><?= number_format($paiements_quotidiens, 0, ',', ' ') ?> Gourdes</p>
    </div>
    <div class="stat-card" style="background:white; padding:20px; border-radius:15px; box-shadow:0 5px 10px rgba(0,0,0,0.05);">
        <h3>Paiements ce mois</h3>
        <p style="font-size:2rem; font-weight:bold;"><?= number_format($paiements_mensuels, 0, ',', ' ') ?> Gourdes</p>
    </div>
    <div class="stat-card" style="background:white; padding:20px; border-radius:15px; box-shadow:0 5px 10px rgba(0,0,0,0.05);">
        <h3>Total général</h3>
        <p style="font-size:2rem; font-weight:bold;"><?= number_format($total_paiements, 0, ',', ' ') ?> Gourdes</p>
    </div>
</div>

<div class="table-actions">
    <form method="GET" class="search-form">
        <label for="date">Rapport journalier:</label>
        <input type="date" name="date" value="<?= $date_filtre ?>">
        <button type="submit">Voir</button>
        <a href="exporter_rapport.php?date=<?= urlencode($date_filtre) ?>" class="btn btn-primary"><i class="fa fa-file-excel"></i> Exporter CSV</a>
    </form>
</div>

<h3>Derniers paiements</h3>
<table class="data-table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Élève</th>
            <th>Montant</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($recents as $r): ?>
        <tr>
            <td><?= $r['payment_date'] ?></td>
            <td><?= htmlspecialchars($r['firstname'] . ' ' . $r['lastname']) ?><br><small><?= $r['matricule'] ?></small></td>
            <td><?= number_format($r['amount'], 0, ',', ' ') ?> Gourdes</td>
            <td><?= $types_paiement[$r['payment_type']] ?? $r['payment_type'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>