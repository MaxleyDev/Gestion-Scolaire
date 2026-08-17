<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

// Si le formulaire est soumis, enregistrer les présences
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classe = $_POST['classe'];
    $date = $_POST['date'];
    $statuts = $_POST['statut'] ?? []; // tableau etudiant_id => statut

    // Supprimer les présences existantes pour cette classe/date
    $stmt = $pdo->prepare("DELETE FROM attendance WHERE class = ? AND session_date = ?");
    $stmt->execute([$classe, $date]);

    // Insérer les nouveaux enregistrements
    $insert = $pdo->prepare("INSERT INTO attendance (student_id, class, session_date, status) VALUES (?, ?, ?, ?)");
    foreach ($statuts as $etudiant_id => $statut) {
        $insert->execute([$etudiant_id, $classe, $date, $statut]);
    }

    header('Location: presence.php');
    exit;
}

// Étape 1 : Sélectionner la classe et la date
$classe_selectionnee = $_GET['classe'] ?? '';
$date_selectionnee = $_GET['date'] ?? date('Y-m-d');
$etudiants = [];

if (!empty($classe_selectionnee)) {
    // Récupérer les élèves de cette classe
    $stmt = $pdo->prepare("SELECT id, lastname, firstname, matricule FROM students WHERE class = ? AND status = 'active' ORDER BY lastname");
    $stmt->execute([$classe_selectionnee]);
    $etudiants = $stmt->fetchAll();

    // Récupérer les présences existantes pour cette classe/date
    $existant = $pdo->prepare("SELECT student_id, status FROM attendance WHERE class = ? AND session_date = ?");
    $existant->execute([$classe_selectionnee, $date_selectionnee]);
    $statuts_existants = [];
    foreach ($existant->fetchAll() as $e) {
        $statuts_existants[$e['student_id']] = $e['status'];
    }
}

// Récupérer les classes distinctes
$classes = $pdo->query("SELECT DISTINCT class FROM students WHERE class IS NOT NULL AND class != '' ORDER BY class")->fetchAll();
?>

<h2>Marquer les présences</h2>

<form method="GET" class="form">
    <div class="form-row">
        <div class="form-group">
            <label>Classe</label>
            <select name="classe" required onchange="this.form.submit()">
                <option value="">-- Choisir une classe --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= htmlspecialchars($c['class']) ?>" <?= $classe_selectionnee == $c['class'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['class']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Date</label>
            <input type="date" name="date" value="<?= $date_selectionnee ?>" onchange="this.form.submit()">
        </div>
    </div>
</form>

<?php if (!empty($classe_selectionnee) && !empty($etudiants)): ?>
<form method="POST" class="form">
    <input type="hidden" name="classe" value="<?= htmlspecialchars($classe_selectionnee) ?>">
    <input type="hidden" name="date" value="<?= htmlspecialchars($date_selectionnee) ?>">
    <table class="data-table">
        <thead>
            <tr>
                <th>Élève</th>
                <th>Matricule</th>
                <th>Présent</th>
                <th>Absent</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($etudiants as $e): 
                $checked_present = (isset($statuts_existants[$e['id']]) && $statuts_existants[$e['id']] == 'present') ? 'checked' : '';
                $checked_absent = (isset($statuts_existants[$e['id']]) && $statuts_existants[$e['id']] == 'absent') ? 'checked' : '';
            ?>
            <tr>
                <td><?= htmlspecialchars($e['firstname'] . ' ' . $e['lastname']) ?></td>
                <td><?= $e['matricule'] ?></td>
                <td>
                    <input type="radio" name="statut[<?= $e['id'] ?>]" value="present" <?= $checked_present ?> required> Présent
                </td>
                <td>
                    <input type="radio" name="statut[<?= $e['id'] ?>]" value="absent" <?= $checked_absent ?>> Absent
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="presence.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>
<?php elseif (!empty($classe_selectionnee)): ?>
<p>Aucun élève actif dans cette classe.</p>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>