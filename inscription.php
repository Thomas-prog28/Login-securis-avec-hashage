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
    $email = trim($_POST["email"]) ?? "";

    //validation basique
    if (strlen($user) < 3) {
        $errors[] = "Le pseudo doit faire au moins 3 caractères";
    }
    if (strlen($password) < 12) {
        $errors[] = "Le mot de passe doit faire au moins 12 caractères";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide";
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

            $insert = $pdo->prepare("INSERT INTO utilisateurs (username, password, email, created_at) VALUES(:username, :password, :email, NOW())");
            $insert->execute([':username' => $user, ':password' => $hashedPassword, ':email' => $email]);
            $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez vous connecter ";
            header("Location: index.php");
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
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Police Western -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">

    <title>Inscription</title>

    <style>
        body {
            font-family: "Cinzel", serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[url('https://images.unsplash.com/photo-1526401485004-2fda9f6d2f6b')] bg-cover bg-center bg-fixed flex items-center justify-center">

    <div class="bg-[#fff8e6]/90 border-4 border-[#8b5a2b] shadow-xl rounded-lg p-10 w-full max-w-md backdrop-blur-sm">

        <h1 class="text-4xl text-center text-[#4b2e0f] drop-shadow-md mb-2">Dashboard des familles</h1>
        <h2 class="text-2xl text-center text-[#4b2e0f] mb-6">Inscription</h2>

        <div class="text-center mb-6">
            <a href="index.php" class="text-[#8b5a2b] font-bold underline hover:text-[#a66c3b]">
                Déjà inscrit ? Connexion
            </a>
        </div>

        <form method="POST" class="space-y-6">

            <div>
                <label for="user" class="block text-lg font-bold text-[#4b2e0f]">Nom d'utilisateur</label>
                <input type="text" name="user" id="user" required
                    class="w-full mt-1 px-4 py-2 border-2 border-[#8b5a2b] rounded bg-[#fff8e6] text-[#3b2f2f] focus:outline-none focus:ring-2 focus:ring-[#a66c3b]">
            </div>

            <div>
                <label for="password" class="block text-lg font-bold text-[#4b2e0f]">Mot de passe</label>
                <input type="password" name="password" id="password" required
                    class="w-full mt-1 px-4 py-2 border-2 border-[#8b5a2b] rounded bg-[#fff8e6] text-[#3b2f2f] focus:outline-none focus:ring-2 focus:ring-[#a66c3b]">
            </div>

            <div>
                <label for="email" class="block text-lg font-bold text-[#4b2e0f]">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full mt-1 px-4 py-2 border-2 border-[#8b5a2b] rounded bg-[#fff8e6] text-[#3b2f2f] focus:outline-none focus:ring-2 focus:ring-[#a66c3b]">
            </div>


            <!-- Zone d'erreurs -->
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc pl-5">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div>
                <input type="submit" value="S'enregistrer"
                    class="w-full bg-[#8b5a2b] hover:bg-[#a66c3b] text-white font-bold py-3 rounded cursor-pointer transition transform hover:scale-105">
            </div>