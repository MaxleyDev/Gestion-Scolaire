<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$id = $_GET['id'] ?? 0;
if (!$id) {
    header('Location: paiements.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM payments WHERE id = ?");
$stmt->execute([$id]);
$paiement = $stmt->fetch();
if (!$paiement) {
    header('Location: paiements.php');
    exit;
}

$etudiants = $pdo->query("SELECT id, lastname, firstname, matricule FROM students ORDER BY lastname")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant_id = $_POST['etudiant_id'];
    $montant = $_POST['montant'];
    $date_paiement = $_POST['date_paiement'];
    $type_paiement = $_POST['type_paiement'];
    $description = $_POST['description'] ?? '';

    $sql = "UPDATE payments SET student_id=?, amount=?, payment_date=?, payment_type=?, description=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$etudiant_id, $montant, $date_paiement, $type_paiement, $description, $id]);

    header('Location: paiements.php');
    exit;
}
?>

<h2>Modifier le paiement</h2>
<form method="POST" class="form">
    <div class="form-group">
        <label>Élève *</label>
        <select name="etudiant_id" required>
            <?php foreach ($etudiants as $e): ?>
                <option value="<?= $e['id'] ?>" <?= $e['id'] == $paiement['student_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['firstname'] . ' ' . $e['lastname'] . ' (' . $e['matricule'] . ')') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Montant *</label>
            <input type="number" step="1" name="montant" value="<?= $paiement['amount'] ?>" required>
        </div>
        <div class="form-group">
            <label>Date *</label>
            <input type="date" name="date_paiement" value="<?= $paiement['payment_date'] ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label>Type de paiement *</label>
        <select name="type_paiement" required>
            <option value="registration" <?= $paiement['payment_type']=='registration'?'selected':'' ?>>Inscription</option>
            <option value="monthly" <?= $paiement['payment_type']=='monthly'?'selected':'' ?>>Mensualité</option>
            <option value="installment" <?= $paiement['payment_type']=='installment'?'selected':'' ?>>Versement</option>
            <option value="balance" <?= $paiement['payment_type']=='balance'?'selected':'' ?>>Solde</option>
        </select>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="2"><?= htmlspecialchars($paiement['description']) ?></textarea>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="paiements.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>