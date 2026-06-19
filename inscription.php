<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST["user"]) ?? "";
    $password = $_POST["password"] ?? "";


    //validation basique
    if (strlen($user) < 3) {
        $errors[] = "Le pseudo doit faire au moins 3 caractères";
    }
    if (strlen($password) < 12) {
        $errors[] = "Le mot de passe doit faire au moins 12 caractères";
    }
    if (empty($errors)) {
        //vérifie si l'utilisateur existe en base de données
        // echo "<pre>";

        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE  username = :username");
        $stmt->execute([':username' => $user]);

        if ($stmt->fetch()) {
            $errors[] = "Le pseudo est déjà utilisé";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            //insérer dans la base de données

            $insert = $pdo->prepare("INSERT INTO utilisateurs (username, password) VALUES(:username, :password)");
            $insert->execute([':username' => $user, ':password' => $hashedPassword]);
            $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez vous connecter ";
            header("Location: connexion.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="style.css"> -->

    <title>Inscription</title>
</head>

<body>
    <h1>Dashboard des familles</h1>
    <h1 class="text-3xl font-bold underline text-red-500">Test Tailwind</h1>
    <p><h2>Inscription</h2>
    <h2><a href="connexion.php">Connexion</a></h2>
    </p>
    <form method="POST">
        <div>
            <label for="user">Nom d'utilisateur</label>
            <input type="text" name="user" id="user" required>
        </div>
        <div>
            <label for="password">mot de passe</label>
            <input type="text" name="password" id="password" required>
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
            <input type="submit" value="S'enregistrer">
        </div>
    </form>
</body>

</html>