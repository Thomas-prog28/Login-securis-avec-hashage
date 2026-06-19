<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="style.css"> -->

    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard des familles</h1>
    <h2>Bonjour <?= htmlspecialchars($_SESSION['name']) ?></h2>

    <a href="deconnexion.php">Se déconnecter</a>
</body>
</html>