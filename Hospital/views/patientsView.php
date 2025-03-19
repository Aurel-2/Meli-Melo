<?php
global $loginController
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>Gestion de dossier médical</title>
    <link href="../public/hospital.css" rel="stylesheet">
</head>
<body onload="init()">
<h1>Gestion des dossiers médicaux</h1>
<?php if (!$loginController->isConnected()): ?>
    <!-- Formulaire de connexion -->
    <form method="POST" class="login-form" action="?action=login">
        <input type="hidden" name="form_type" value="login">
        <h2>Connexion</h2>
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
        </div>
        <div class="form-group">
            <button type="submit" class="login-btn">Se connecter</button>
        </div>
    </form>
<?php else: ?>
<div class="container">
        <form id="medical-form" method="post" action="../public/index.php?action=create">
            <div>
                <label for="id">ID</label>
                <input id="id" name="id" type="number">
            </div>
            <div>
                <label for="name">Nom</label>
                <input id="name" name="name" type="text">
            </div>
            <div>
                <label for="age">Age</label>
                <input id="age" name="age" type="number">
            </div>
            <div>
                <label for="date">Date</label>
                <input id="date" name="date" type="date">
            </div>
            <div>
                <label for="diag">Diagnostic</label>
                <input id="diag" name="diag" type="text">
            </div>
            <input type="submit" value="Ajouter">
            <?php echo $message ?? ''; ?>
        </form>
    </div>
    <div id="search">
        <label for="filter">Filtrer</label>
        <input id="filter" name="filter" placeholder="Filtrer ici..." type="text">
        <div id="stats">Nombre total de patients: <?php echo count($patients ?? []); ?></div>
        <div id="results"></div>
    </div>
<?php endif; ?>

<script src="../public/hospital.js" type="text/javascript"></script>
</body>
</html>