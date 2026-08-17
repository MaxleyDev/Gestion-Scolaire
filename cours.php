<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

// Récupérer les cours avec les noms des professeurs
$sql = "SELECT courses.*, teachers.lastname as t_last, teachers.firstname as t_first 
        FROM courses 
        LEFT JOIN teachers ON courses.teacher_id = teachers.id 
        ORDER BY courses.id DESC";
$stmt = $pdo->query($sql);
$cours = $stmt->fetchAll();
?>

<h2>Gestion des cours</h2>
<div class="table-actions">
    <a href="ajouter_cours.php" class="btn btn-primary">Ajouter un cours</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Nom du cours</th>
            <th>Description</th>
            <th>Professeur assigné</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cours as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><?= htmlspecialchars($c['description']) ?></td>
            <td>
                <?php if ($c['teacher_id']): ?>
                    <?= htmlspecialchars($c['t_first'] . ' ' . $c['t_last']) ?>
                <?php else: ?>
                    <em>Non assigné</em>
                <?php endif; ?>
            </td>
            <td class="actions">
                <a href="modifier_cours.php?id=<?= $c['id'] ?>" class="btn-edit"><i class="fa fa-edit"></i></a>
                <a href="supprimer_cours.php?id=<?= $c['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer ce cours ?')"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>