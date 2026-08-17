<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$etudiants = $pdo->query("SELECT id, lastname, firstname, matricule FROM students ORDER BY lastname")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Générer le numéro de facture : INV-AAAA-MM-XXXX
    $annee = date('Y');
    $mois = date('m');
    // Obtenir le dernier numéro de facture pour ce mois
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM invoices WHERE invoice_no LIKE ?");
    $like = "INV-$annee-$mois-%";
    $stmt->execute([$like]);
    $count = $stmt->fetch()['count'] + 1;
    $invoice_no = "INV-$annee-$mois-" . str_pad($count, 4, '0', STR_PAD_LEFT);

    $etudiant_id = $_POST['etudiant_id'];
    $montant = $_POST['montant'];
    $type_paiement = $_POST['type_paiement'];
    $date_emission = $_POST['date_emission'];
    $statut = $_POST['statut'];

    $sql = "INSERT INTO invoices (invoice_no, student_id, amount, payment_type, issue_date, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$invoice_no, $etudiant_id, $montant, $type_paiement, $date_emission, $statut]);

    header('Location: factures.php');
    exit;
}
?>

<h2>Créer une facture</h2>
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
            <label>Type de paiement</label>
            <input type="text" name="type_paiement" placeholder="ex: Inscription, Mensualité">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Date d'émission *</label>
            <input type="date" name="date_emission" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
            <label>Statut</label>
            <select name="statut">
                <option value="pending">En attente</option>
                <option value="paid">Payée</option>
            </select>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Générer</button>
        <a href="factures.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>