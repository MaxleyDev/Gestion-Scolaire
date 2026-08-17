<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: connexion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord — École</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="header">
    <div class="top-row">
        <div class="title">
            <div class="title-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="title-text">
                <h1>Tableau de bord — École</h1>
                <p>Gestion rapide : élèves, professeurs, paiements, rapports</p>
            </div>
        </div>
        <div class="actions">
            <a href="profil.php" class="btn"><i class="fa-regular fa-circle-user"></i> Profil</a>
            <a href="deconnexion.php" class="btn" style="background: transparent; border: 2px solid white; color: white;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</a>
        </div>
    </div>
    <div class="search-wrapper">
    <form method="GET" action="recherche.php" style="display: contents;">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="q" placeholder="Rechercher : nom élève, téléphone, classe, reçu..." 
                   value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
            <div class="shortcut">Ctrl + K</div>
        </div>
    </form>
</div>
    <nav class="main-nav">
    <a href="index.php">Dashboard</a>
    <a href="etudiants.php">Élèves</a>
<a href="enseignants.php">Professeurs</a>
<a href="cours.php">Cours</a>
<a href="paiements.php">Paiements</a>
<a href="factures.php">Factures</a>
<a href="presence.php">Présences</a>
<a href="rapport.php">Rapports</a>
</nav>
</div>
<div class="container">