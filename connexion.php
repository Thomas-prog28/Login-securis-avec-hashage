<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = trim($_POST['user'] ?? "");
    $password = $_POST['password'] ?? "";

    if (!empty($user) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = :username");
        $stmt->execute([':username' => $user]);
        $read = $stmt->fetch();

        if ($read && password_verify($password, $read['password'])) {
            session_regenerate_id(true);

            //stocker des informations utiles de l'utilisateur
            $_SESSION['user_id'] = $read['id'];
            $_SESSION['name'] = $read['username'];

            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Identifiant ou mot de passe incorrect";
        }
    } else {
        $errors[] = "Veuillez remplir tous les champs";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="style.css"> -->

    <title>Connexion</title>
</head>

<body>
    <h1>Dashboard des familles</h1>
    <p>
    <h2>Connexion</h2>
    <h2><a href="inscription.php">S'enregistrer</a></h2>
    </p>
    <form method="POST">
        <div>
            <label for="user">Nom d'utilisateur</label>
            <input type="text" name="user" id="user" required>
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="text" name="password" id="password">
        </div>
        <div>
            <?php if (!empty($errors)): ?>
                <ul style="color:red;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <div>
            <input type="submit" value="Se connecter">
        </div>
    </form>
</body>

</html>