<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$filtre_classe = $_GET['classe'] ?? '';
$filtre_date = $_GET['date'] ?? date('Y-m-d');

// Récupérer les classes distinctes
$classes = $pdo->query("SELECT DISTINCT class FROM students WHERE class IS NOT NULL AND class != '' ORDER BY class")->fetchAll();

$sql = "SELECT attendance.*, students.lastname, students.firstname, students.matricule 
        FROM attendance 
        JOIN students ON attendance.student_id = students.id 
        WHERE 1=1";
$params = [];
if (!empty($filtre_classe)) {
    $sql .= " AND attendance.class = ?";
    $params[] = $filtre_classe;
}
if (!empty($filtre_date)) {
    $sql .= " AND attendance.session_date = ?";
    $params[] = $filtre_date;
}
$sql .= " ORDER BY attendance.session_date DESC, students.lastname";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$presences = $stmt->fetchAll();
?>

<h2>Suivi des présences</h2>
<div class="table-actions">
    <a href="marquer_presence.php" class="btn btn-primary">Marquer les présences</a>
    <form method="GET" class="search-form">
        <select name="classe">
            <option value="">Toutes les classes</option>
            <?php foreach ($classes as $c): ?>
                <option value="<?= htmlspecialchars($c['class']) ?>" <?= $filtre_classe == $c['class'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['class']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="date" value="<?= $filtre_date ?>">
        <button type="submit"><i class="fa fa-filter"></i> Filtrer</button>
        <a href="exporter_presence.php?classe=<?= urlencode($filtre_classe) ?>&date=<?= urlencode($filtre_date) ?>" class="btn btn-primary"><i class="fa fa-file-excel"></i> Exporter CSV</a>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Élève</th>
            <th>Classe</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($presences as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['session_date']) ?></td>
            <td><?= htmlspecialchars($p['firstname'] . ' ' . $p['lastname']) ?><br><small><?= $p['matricule'] ?></small></td>
            <td><?= htmlspecialchars($p['class']) ?></td>
            <td>
                <span class="status-badge status-<?= $p['status'] ?>">
                    <?= $p['status'] == 'present' ? 'Présent' : 'Absent' ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>