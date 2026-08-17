<?php
require_once 'includes/header.php';
require_once 'includes/config.php';

$terme_recherche = isset($_GET['q']) ? trim($_GET['q']) : '';
$resultats = [];

if (!empty($terme_recherche)) {
    // Recherche dans les élèves
    $stmt = $pdo->prepare("SELECT id, matricule, lastname, firstname, phone, class, status 
                            FROM students 
                            WHERE lastname LIKE ? OR firstname LIKE ? OR phone LIKE ? OR class LIKE ? OR matricule LIKE ?
                            ORDER BY lastname");
    $like = "%$terme_recherche%";
    $stmt->execute([$like, $like, $like, $like, $like]);
    $resultats['etudiants'] = $stmt->fetchAll();

    // Recherche dans les enseignants
    $stmt = $pdo->prepare("SELECT id, lastname, firstname, phone, email, specialty 
                            FROM teachers 
                            WHERE lastname LIKE ? OR firstname LIKE ? OR phone LIKE ? OR email LIKE ? OR specialty LIKE ?
                            ORDER BY lastname");
    $stmt->execute([$like, $like, $like, $like, $like]);
    $resultats['enseignants'] = $stmt->fetchAll();

    // Recherche dans les paiements (via nom/prénom/matricule de l'élève ou description)
    $stmt = $pdo->prepare("SELECT payments.*, students.lastname as s_last, students.firstname as s_first, students.matricule 
                            FROM payments 
                            JOIN students ON payments.student_id = students.id 
                            WHERE students.lastname LIKE ? OR students.firstname LIKE ? OR students.matricule LIKE ? OR payments.description LIKE ?
                            ORDER BY payments.payment_date DESC");
    $stmt->execute([$like, $like, $like, $like]);
    $resultats['paiements'] = $stmt->fetchAll();

    // Recherche dans les factures (numéro de facture ou nom/prénom/matricule de l'élève)
    $stmt = $pdo->prepare("SELECT invoices.*, students.lastname as s_last, students.firstname as s_first, students.matricule 
                            FROM invoices 
                            JOIN students ON invoices.student_id = students.id 
                            WHERE invoices.invoice_no LIKE ? OR students.lastname LIKE ? OR students.firstname LIKE ? OR students.matricule LIKE ?
                            ORDER BY invoices.issue_date DESC");
    $stmt->execute([$like, $like, $like, $like]);
    $resultats['factures'] = $stmt->fetchAll();
}
?>

<h2>Résultats de recherche pour "<?= htmlspecialchars($terme_recherche) ?>"</h2>

<?php if (empty($terme_recherche)): ?>
    <p>Veuillez saisir un terme de recherche.</p>
<?php elseif (empty(array_filter($resultats))): ?>
    <p>Aucun résultat trouvé.</p>
<?php else: ?>

    <?php if (!empty($resultats['etudiants'])): ?>
        <h3>Élèves</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Classe</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultats['etudiants'] as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['matricule']) ?></td>
                    <td><?= htmlspecialchars($e['lastname']) ?></td>
                    <td><?= htmlspecialchars($e['firstname']) ?></td>
                    <td><?= htmlspecialchars($e['phone']) ?></td>
                    <td><?= htmlspecialchars($e['class']) ?></td>
                    <td><span class="status-badge status-<?= $e['status'] ?>"><?= $e['status'] == 'active' ? 'Actif' : 'Inactif' ?></span></td>
                    <td><a href="modifier_etudiant.php?id=<?= $e['id'] ?>" class="btn-edit"><i class="fa fa-edit"></i></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!empty($resultats['enseignants'])): ?>
        <h3>Professeurs</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Spécialité</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultats['enseignants'] as $ens): ?>
                <tr>
                    <td><?= htmlspecialchars($ens['lastname']) ?></td>
                    <td><?= htmlspecialchars($ens['firstname']) ?></td>
                    <td><?= htmlspecialchars($ens['phone']) ?></td>
                    <td><?= htmlspecialchars($ens['email']) ?></td>
                    <td><?= htmlspecialchars($ens['specialty']) ?></td>
                    <td><a href="modifier_enseignant.php?id=<?= $ens['id'] ?>" class="btn-edit"><i class="fa fa-edit"></i></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!empty($resultats['paiements'])): ?>
        <h3>Paiements</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Élève</th>
                    <th>Montant</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultats['paiements'] as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['payment_date']) ?></td>
                    <td><?= htmlspecialchars($p['s_first'] . ' ' . $p['s_last']) ?><br><small><?= $p['matricule'] ?></small></td>
                    <td><?= number_format($p['amount'], 0, ',', ' ') ?> Gourdes</td>
                    <td><?= htmlspecialchars($p['payment_type']) ?></td>
                    <td><?= htmlspecialchars($p['description']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!empty($resultats['factures'])): ?>
        <h3>Factures</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>N° Facture</th>
                    <th>Élève</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultats['factures'] as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f['invoice_no']) ?></td>
                    <td><?= htmlspecialchars($f['s_first'] . ' ' . $f['s_last']) ?><br><small><?= $f['matricule'] ?></small></td>
                    <td><?= number_format($f['amount'], 0, ',', ' ') ?> Gourdes</td>
                    <td><?= htmlspecialchars($f['issue_date']) ?></td>
                    <td><span class="status-badge status-<?= $f['status'] ?>"><?= $f['status'] == 'paid' ? 'Payée' : 'En attente' ?></span></td>
                    <td><a href="imprimer_facture.php?id=<?= $f['id'] ?>" target="_blank" class="btn-edit"><i class="fa fa-print"></i></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>