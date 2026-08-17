<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$id = $_GET['id'] ?? 0;
if (!$id) {
    header('Location: enseignants.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM teachers WHERE id = ?");
$stmt->execute([$id]);
$enseignant = $stmt->fetch();
if (!$enseignant) {
    header('Location: enseignants.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'telephone' => $_POST['telephone'],
        'email' => $_POST['email'],
        'specialite' => $_POST['specialite'],
        'date_embauche' => $_POST['date_embauche'],
        'id' => $id
    ];

    $sql = "UPDATE teachers SET lastname=:nom, firstname=:prenom, phone=:telephone, email=:email, specialty=:specialite, hire_date=:date_embauche WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    header('Location: enseignants.php');
    exit;
}
?>

<h2>Modifier le professeur</h2>
<form method="POST" class="form">
    <div class="form-row">
        <div class="form-group">
            <label>Nom *</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($enseignant['lastname']) ?>" required>
        </div>
        <div class="form-group">
            <label>Prénom *</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($enseignant['firstname']) ?>" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Téléphone</label>
            <input type="text" name="telephone" value="<?= htmlspecialchars($enseignant['phone']) ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($enseignant['email']) ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Spécialité</label>
            <input type="text" name="specialite" value="<?= htmlspecialchars($enseignant['specialty']) ?>">
        </div>
        <div class="form-group">
            <label>Date d'embauche *</label>
            <input type="date" name="date_embauche" value="<?= $enseignant['hire_date'] ?>" required>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="enseignants.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>