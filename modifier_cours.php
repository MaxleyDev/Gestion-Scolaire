<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$id = $_GET['id'] ?? 0;
if (!$id) {
    header('Location: cours.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);
$cours = $stmt->fetch();
if (!$cours) {
    header('Location: cours.php');
    exit;
}

$enseignants = $pdo->query("SELECT id, lastname, firstname FROM teachers ORDER BY lastname")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $enseignant_id = !empty($_POST['enseignant_id']) ? $_POST['enseignant_id'] : null;

    $sql = "UPDATE courses SET name=?, description=?, teacher_id=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $description, $enseignant_id, $id]);

    header('Location: cours.php');
    exit;
}
?>

<h2>Modifier le cours</h2>
<form method="POST" class="form">
    <div class="form-group">
        <label>Nom du cours *</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($cours['name']) ?>" required>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3"><?= htmlspecialchars($cours['description']) ?></textarea>
    </div>
    <div class="form-group">
        <label>Assigner un professeur</label>
        <select name="enseignant_id">
            <option value="">-- Aucun --</option>
            <?php foreach ($enseignants as $e): ?>
                <option value="<?= $e['id'] ?>" <?= $e['id'] == $cours['teacher_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['firstname'] . ' ' . $e['lastname']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="cours.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>