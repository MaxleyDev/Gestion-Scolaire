<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$enseignants = $pdo->query("SELECT * FROM teachers ORDER BY id DESC")->fetchAll();
?>

<h2>Liste des professeurs</h2>
<div class="table-actions">
    <a href="ajouter_enseignant.php" class="btn btn-primary">Ajouter un professeur</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Téléphone</th>
            <th>Email</th>
            <th>Spécialité</th>
            <th>Date d'embauche</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($enseignants as $ens): ?>
        <tr>
            <td><?= htmlspecialchars($ens['lastname']) ?></td>
            <td><?= htmlspecialchars($ens['firstname']) ?></td>
            <td><?= htmlspecialchars($ens['phone']) ?></td>
            <td><?= htmlspecialchars($ens['email']) ?></td>
            <td><?= htmlspecialchars($ens['specialty']) ?></td>
            <td><?= htmlspecialchars($ens['hire_date']) ?></td>
            <td class="actions">
                <a href="modifier_enseignant.php?id=<?= $ens['id'] ?>" class="btn-edit"><i class="fa fa-edit"></i></a>
                <a href="supprimer_enseignant.php?id=<?= $ens['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer ce professeur ?')"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>