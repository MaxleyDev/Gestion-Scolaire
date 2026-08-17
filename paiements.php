<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$filtre_etudiant = $_GET['etudiant_id'] ?? '';

$sql = "SELECT payments.*, students.lastname, students.firstname, students.matricule 
        FROM payments 
        JOIN students ON payments.student_id = students.id";
$params = [];
if (!empty($filtre_etudiant)) {
    $sql .= " WHERE students.id = ?";
    $params[] = $filtre_etudiant;
}
$sql .= " ORDER BY payments.payment_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$paiements = $stmt->fetchAll();

// Pour la liste déroulante de filtre
$etudiants = $pdo->query("SELECT id, lastname, firstname, matricule FROM students ORDER BY lastname")->fetchAll();
?>

<h2>Gestion des paiements</h2>
<div class="table-actions">
    <a href="ajouter_paiement.php" class="btn btn-primary">Enregistrer un paiement</a>
    <form method="GET" class="search-form">
        <select name="etudiant_id">
            <option value="">Tous les élèves</option>
            <?php foreach ($etudiants as $e): ?>
                <option value="<?= $e['id'] ?>" <?= $filtre_etudiant == $e['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['firstname'] . ' ' . $e['lastname'] . ' (' . $e['matricule'] . ')') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit"><i class="fa fa-filter"></i> Filtrer</button>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Élève</th>
            <th>Montant</th>
            <th>Date</th>
            <th>Type</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($paiements as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['firstname'] . ' ' . $p['lastname']) ?><br><small><?= $p['matricule'] ?></small></td>
            <td><?= number_format($p['amount'], 0, ',', ' ') ?> Gourdes</td>
            <td><?= htmlspecialchars($p['payment_date']) ?></td>
            <td>
                <?php
                $types = ['registration' => 'Inscription', 'monthly' => 'Mensualité', 'installment' => 'Versement', 'balance' => 'Solde'];
                echo $types[$p['payment_type']] ?? $p['payment_type'];
                ?>
            </td>
            <td><?= htmlspecialchars($p['description']) ?></td>
            <td class="actions">
                <a href="modifier_paiement.php?id=<?= $p['id'] ?>" class="btn-edit"><i class="fa fa-edit"></i></a>
                <a href="supprimer_paiement.php?id=<?= $p['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer ce paiement ?')"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>