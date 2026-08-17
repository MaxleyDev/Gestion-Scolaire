<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$id = $_GET['id'] ?? 0;
if (!$id) {
    header('Location: etudiants.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$etudiant = $stmt->fetch();
if (!$etudiant) {
    header('Location: etudiants.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'genre' => $_POST['genre'],
        'date_naissance' => $_POST['date_naissance'],
        'lieu_naissance' => $_POST['lieu_naissance'],
        'telephone' => $_POST['telephone'],
        'email' => $_POST['email'],
        'adresse' => $_POST['adresse'],
        'nif' => $_POST['nif'],
        'classe' => $_POST['classe'],
        'niveau_etude' => $_POST['niveau_etude'],
        'date_inscription' => $_POST['date_inscription'],
        'option_cours' => $_POST['option_cours'],
        'horaire' => $_POST['horaire'],
        'ref_personne' => $_POST['ref_personne'],
        'ref_adresse' => $_POST['ref_adresse'],
        'ref_profession' => $_POST['ref_profession'],
        'ref_telephone' => $_POST['ref_telephone'],
        'id' => $id
    ];

    $sql = "UPDATE students SET lastname=:nom, firstname=:prenom, gender=:genre, birth_date=:date_naissance, birth_place=:lieu_naissance, phone=:telephone, email=:email, address=:adresse, nif=:nif, class=:classe, level_study=:niveau_etude, registration_date=:date_inscription, course_option=:option_cours, schedule=:horaire, ref_person=:ref_personne, ref_address=:ref_adresse, ref_profession=:ref_profession, ref_phone=:ref_telephone WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    header('Location: etudiants.php');
    exit;
}
?>

<h2>Modifier l'élève</h2>
<form method="POST" class="form">
    <div class="form-row">
        <div class="form-group">
            <label>Nom *</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($etudiant['lastname']) ?>" required>
        </div>
        <div class="form-group">
            <label>Prénom *</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($etudiant['firstname']) ?>" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Genre *</label>
            <select name="genre" required>
                <option value="M" <?= $etudiant['gender']=='M'?'selected':'' ?>>Masculin</option>
                <option value="F" <?= $etudiant['gender']=='F'?'selected':'' ?>>Féminin</option>
            </select>
        </div>
        <div class="form-group">
            <label>Date de naissance *</label>
            <input type="date" name="date_naissance" value="<?= $etudiant['birth_date'] ?>" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Lieu de naissance</label>
            <input type="text" name="lieu_naissance" value="<?= htmlspecialchars($etudiant['birth_place']) ?>">
        </div>
        <div class="form-group">
            <label>Téléphone</label>
            <input type="text" name="telephone" value="<?= htmlspecialchars($etudiant['phone']) ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($etudiant['email']) ?>">
        </div>
        <div class="form-group">
            <label>Adresse</label>
            <input type="text" name="adresse" value="<?= htmlspecialchars($etudiant['address']) ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>NIF</label>
            <input type="text" name="nif" value="<?= htmlspecialchars($etudiant['nif']) ?>">
        </div>
        <div class="form-group">
            <label>Classe</label>
            <input type="text" name="classe" value="<?= htmlspecialchars($etudiant['class']) ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Niveau d'étude</label>
            <input type="text" name="niveau_etude" value="<?= htmlspecialchars($etudiant['level_study']) ?>">
        </div>
        <div class="form-group">
            <label>Date d'inscription *</label>
            <input type="date" name="date_inscription" value="<?= $etudiant['registration_date'] ?>" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Option</label>
            <input type="text" name="option_cours" value="<?= htmlspecialchars($etudiant['course_option']) ?>">
        </div>
        <div class="form-group">
            <label>Horaire</label>
            <input type="text" name="horaire" value="<?= htmlspecialchars($etudiant['schedule']) ?>">
        </div>
    </div>
    <h3>Personne de référence</h3>
    <div class="form-row">
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="ref_personne" value="<?= htmlspecialchars($etudiant['ref_person']) ?>">
        </div>
        <div class="form-group">
            <label>Adresse</label>
            <input type="text" name="ref_adresse" value="<?= htmlspecialchars($etudiant['ref_address']) ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Profession</label>
            <input type="text" name="ref_profession" value="<?= htmlspecialchars($etudiant['ref_profession']) ?>">
        </div>
        <div class="form-group">
            <label>Téléphone</label>
            <input type="text" name="ref_telephone" value="<?= htmlspecialchars($etudiant['ref_phone']) ?>">
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="etudiants.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>