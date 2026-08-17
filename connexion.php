<?php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_utilisateur = trim($_POST['nom_utilisateur'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if (!empty($nom_utilisateur) && !empty($mot_de_passe)) {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$nom_utilisateur]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($mot_de_passe, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: index.php');
            exit;
        } else {
            $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #e0e0e0;
            --light-shadow: #ffffff;
            --dark-shadow: #a3b1c6;
            --main-text-color: #333;
            --accent-color: #6a67ce;
            --accent-hover: #7b78e4;
            --accent-active: #5c59b9;
        }
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--bg-color) 0%, #cacaca 50%, var(--bg-color) 100%);
            background-size: 400% 400%;
            animation: backgroundAnimation 15s ease infinite alternate;
            color: var(--main-text-color);
            overflow: hidden;
        }
        @keyframes backgroundAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .login-container {
            background: var(--bg-color);
            padding: 40px;
            border-radius: 30px;
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
            text-align: center;
            width: 350px;
            max-width: 90%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            z-index: 1;
        }
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 15px 15px 30px var(--dark-shadow), -15px -15px 30px var(--light-shadow);
        }
        .login-container h2 {
            font-size: 2.5em;
            margin-bottom: 30px;
            color: var(--main-text-color);
            text-shadow: 1px 1px 2px var(--light-shadow);
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--main-text-color);
            font-size: 0.95em;
        }
        .form-group input {
            width: calc(100% - 40px);
            padding: 15px 20px;
            border: none;
            outline: none;
            border-radius: 15px;
            background: var(--bg-color);
            font-size: 1em;
            color: var(--main-text-color);
            box-shadow: inset 5px 5px 10px var(--dark-shadow), inset -5px -5px 10px var(--light-shadow);
            transition: box-shadow 0.3s ease, transform 0.2s ease;
            font-weight: 400;
        }
        .form-group input:focus {
            box-shadow: inset 3px 3px 7px var(--dark-shadow), inset -3px -3px 7px var(--light-shadow), 0 0 0 3px var(--accent-color);
            transform: scale(1.01);
        }
        .login-button {
            width: 100%;
            padding: 18px;
            border: none;
            outline: none;
            border-radius: 25px;
            background: var(--accent-color);
            color: white;
            font-size: 1.2em;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 7px 7px 15px var(--dark-shadow), -7px -7px 15px var(--light-shadow);
            transition: all 0.3s ease;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .login-button:hover {
            background: var(--accent-hover);
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
            transform: translateY(-3px);
        }
        .login-button:active {
            background: var(--accent-active);
            box-shadow: inset 3px 3px 7px var(--dark-shadow), inset -3px -3px 7px var(--light-shadow);
            transform: translateY(1px);
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>
        <?php if (isset($erreur)): ?>
            <div class="error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nom_utilisateur">Nom d'utilisateur</label>
                <input type="text" id="nom_utilisateur" name="nom_utilisateur" placeholder="Entrez votre nom d'utilisateur" required>
            </div>
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Entrez votre mot de passe" required>
            </div>
            <button type="submit" class="login-button">Se connecter</button>
        </form>
    </div>
</body>
</html>