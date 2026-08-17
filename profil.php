<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$message = '';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actuel = $_POST['mot_de_passe_actuel'] ?? '';
    $nouveau = $_POST['nouveau_mot_de_passe'] ?? '';
    $confirmation = $_POST['confirmation_mot_de_passe'] ?? '';

    // Récupérer l'admin connecté
    $nom_utilisateur = $_SESSION['admin_username'];

    // Récupérer le hash actuel
    $stmt = $pdo->prepare("SELECT password FROM admin WHERE username = ?");
    $stmt->execute([$nom_utilisateur]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($actuel, $admin['password'])) {
        if ($nouveau === $confirmation) {
            if (strlen($nouveau) >= 6) {
                $nouveauHash = password_hash($nouveau, PASSWORD_DEFAULT);
                $update = $pdo->prepare("UPDATE admin SET password = ? WHERE username = ?");
                $update->execute([$nouveauHash, $nom_utilisateur]);
                $message = "Mot de passe modifié avec succès.";
            } else {
                $erreur = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
            }
        } else {
            $erreur = "La confirmation du mot de passe ne correspond pas.";
        }
    } else {
        $erreur = "Mot de passe actuel incorrect.";
    }
}
?>

<h2>Profil administrateur</h2>
<p>Connecté en tant que : <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></p>

<?php if ($message): ?>
    <div class="alert success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($erreur): ?>
    <div class="alert error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form method="POST" class="form" style="max-width: 500px;">
    <div class="form-group">
        <label>Mot de passe actuel *</label>
        <input type="password" name="mot_de_passe_actuel" required>
    </div>
    <div class="form-group">
        <label>Nouveau mot de passe * (min. 6 caractères)</label>
        <input type="password" name="nouveau_mot_de_passe" required minlength="6">
    </div>
    <div class="form-group">
        <label>Confirmer le nouveau mot de passe *</label>
        <input type="password" name="confirmation_mot_de_passe" required>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
        <a href="index.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<style>
.alert {
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
}
.alert.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.alert.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<?php require_once 'includes/footer.php'; ?>