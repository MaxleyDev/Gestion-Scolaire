<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

// Récupérer tous les enseignants pour la liste déroulante
$enseignants = $pdo->query("SELECT id, lastname, firstname FROM teachers ORDER BY lastname")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $enseignant_id = !empty($_POST['enseignant_id']) ? $_POST['enseignant_id'] : null;

    $sql = "INSERT INTO courses (name, description, teacher_id) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $description, $enseignant_id]);

    header('Location: cours.php');
    exit;
}
?>

<h2>Ajouter un cours</h2>
<form method="POST" class="form">
    <div class="form-group">
        <label>Nom du cours *</label>
        <input type="text" name="nom" required>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label>Assigner un professeur (optionnel)</label>
        <select name="enseignant_id">
            <option value="">-- Aucun --</option>
            <?php foreach ($enseignants as $e): ?>
                <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['firstname'] . ' ' . $e['lastname']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="cours.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>