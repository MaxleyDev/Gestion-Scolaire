<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$etudiants = $pdo->query("SELECT id, lastname, firstname, matricule FROM students ORDER BY lastname")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant_id = $_POST['etudiant_id'];
    $montant = $_POST['montant'];
    $date_paiement = $_POST['date_paiement'];
    $type_paiement = $_POST['type_paiement'];
    $description = $_POST['description'] ?? '';

    $sql = "INSERT INTO payments (student_id, amount, payment_date, payment_type, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$etudiant_id, $montant, $date_paiement, $type_paiement, $description]);

    header('Location: paiements.php');
    exit;
}
?>

<h2>Enregistrer un paiement</h2>
<form method="POST" class="form">
    <div class="form-group">
        <label>Élève *</label>
        <select name="etudiant_id" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($etudiants as $e): ?>
                <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['firstname'] . ' ' . $e['lastname'] . ' (' . $e['matricule'] . ')') ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Montant *</label>
            <input type="number" step="1" name="montant" required>
        </div>
        <div class="form-group">
            <label>Date *</label>
            <input type="date" name="date_paiement" value="<?= date('Y-m-d') ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label>Type de paiement *</label>
        <select name="type_paiement" required>
            <option value="registration">Inscription</option>
            <option value="monthly">Mensualité</option>
            <option value="installment">Versement</option>
            <option value="balance">Solde</option>
        </select>
    </div>
    <div class="form-group">
        <label>Description (optionnelle)</label>
        <textarea name="description" rows="2"></textarea>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="paiements.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>