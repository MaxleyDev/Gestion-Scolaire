<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$recherche = $_GET['recherche'] ?? '';
$sql = "SELECT * FROM students WHERE lastname LIKE ? OR firstname LIKE ? OR phone LIKE ? OR class LIKE ? ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$terme = "%$recherche%";
$stmt->execute([$terme, $terme, $terme, $terme]);
$etudiants = $stmt->fetchAll();
?>

<h2>Liste des élèves</h2>
<div class="table-actions">
    <a href="ajouter_etudiant.php" class="btn btn-primary">Ajouter un élève</a>
    <form method="GET" class="search-form">
        <input type="text" name="recherche" placeholder="Rechercher..." value="<?= htmlspecialchars($recherche) ?>">
        <button type="submit"><i class="fa fa-search"></i></button>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Genre</th>
            <th>Téléphone</th>
            <th>Classe</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($etudiants as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e['matricule']) ?></td>
            <td><?= htmlspecialchars($e['lastname']) ?></td>
            <td><?= htmlspecialchars($e['firstname']) ?></td>
            <td><?= $e['gender'] == 'M' ? 'Masculin' : 'Féminin' ?></td>
            <td><?= htmlspecialchars($e['phone']) ?></td>
            <td><?= htmlspecialchars($e['class']) ?></td>
            <td>
                <a href="changer_statut_etudiant.php?id=<?= $e['id'] ?>" class="status-badge status-<?= $e['status'] ?>">
                    <?= $e['status'] == 'active' ? 'Actif' : 'Inactif' ?>
                </a>
            </td>
            <td class="actions">
                <a href="modifier_etudiant.php?id=<?= $e['id'] ?>" class="btn-edit"><i class="fa fa-edit"></i></a>
                <a href="supprimer_etudiant.php?id=<?= $e['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer cet élève ?')"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>