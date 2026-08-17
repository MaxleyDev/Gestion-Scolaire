<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'telephone' => $_POST['telephone'],
        'email' => $_POST['email'],
        'specialite' => $_POST['specialite'],
        'date_embauche' => $_POST['date_embauche']
    ];

    $sql = "INSERT INTO teachers (lastname, firstname, phone, email, specialty, hire_date) 
            VALUES (:nom, :prenom, :telephone, :email, :specialite, :date_embauche)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    header('Location: enseignants.php');
    exit;
}
?>

<h2>Ajouter un professeur</h2>
<form method="POST" class="form">
    <div class="form-row">
        <div class="form-group">
            <label>Nom *</label>
            <input type="text" name="nom" required>
        </div>
        <div class="form-group">
            <label>Prénom *</label>
            <input type="text" name="prenom" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Téléphone</label>
            <input type="text" name="telephone">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Spécialité</label>
            <input type="text" name="specialite">
        </div>
        <div class="form-group">
            <label>Date d'embauche *</label>
            <input type="date" name="date_embauche" required>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="enseignants.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>