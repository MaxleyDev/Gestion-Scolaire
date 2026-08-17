<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Générer le matricule : année en cours + 4 chiffres aléatoires
    $annee = date('Y');
    $aleatoire = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $matricule = "ETU-$annee-$aleatoire";

    $data = [
        'matricule' => $matricule,
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
        'ref_telephone' => $_POST['ref_telephone']
    ];

    $sql = "INSERT INTO students (matricule, lastname, firstname, gender, birth_date, birth_place, phone, email, address, nif, class, level_study, registration_date, course_option, schedule, ref_person, ref_address, ref_profession, ref_phone) 
            VALUES (:matricule, :nom, :prenom, :genre, :date_naissance, :lieu_naissance, :telephone, :email, :adresse, :nif, :classe, :niveau_etude, :date_inscription, :option_cours, :horaire, :ref_personne, :ref_adresse, :ref_profession, :ref_telephone)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    header('Location: etudiants.php');
    exit;
}
?>

<h2>Ajouter un élève</h2>
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
            <label>Genre *</label>
            <select name="genre" required>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
            </select>
        </div>
        <div class="form-group">
            <label>Date de naissance *</label>
            <input type="date" name="date_naissance" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Lieu de naissance</label>
            <input type="text" name="lieu_naissance">
        </div>
        <div class="form-group">
            <label>Téléphone</label>
            <input type="text" name="telephone">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email">
        </div>
        <div class="form-group">
            <label>Adresse</label>
            <input type="text" name="adresse">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>NIF</label>
            <input type="text" name="nif">
        </div>
        <div class="form-group">
            <label>Classe</label>
            <input type="text" name="classe">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Niveau d'étude</label>
            <input type="text" name="niveau_etude">
        </div>
        <div class="form-group">
            <label>Date d'inscription *</label>
            <input type="date" name="date_inscription" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Option</label>
            <input type="text" name="option_cours">
        </div>
        <div class="form-group">
            <label>Horaire</label>
            <input type="text" name="horaire">
        </div>
    </div>
    <h3>Personne de référence</h3>
    <div class="form-row">
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="ref_personne">
        </div>
        <div class="form-group">
            <label>Adresse</label>
            <input type="text" name="ref_adresse">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Profession</label>
            <input type="text" name="ref_profession">
        </div>
        <div class="form-group">
            <label>Téléphone</label>
            <input type="text" name="ref_telephone">
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="etudiants.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>