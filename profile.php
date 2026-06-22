<?php
require_once 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

// Vérifier que l'ID est présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID manquant.");
}

// Sécuriser l'ID
$id = (int) $_GET['id'];

// Récupérer l'utilisateur correspondant
$stmt = $pdo->prepare("SELECT username, email, created_at FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Cinzel", serif;
        }
    </style>
    <title>Profil utilisateur</title>
</head>

<body class="min-h-screen bg-[url('https://images.unsplash.com/photo-1526401485004-2fda9f6d2f6b')] 
             bg-cover bg-center bg-fixed flex flex-col items-center justify-center">

    <div class="bg-[#fff8e6]/90 border-4 border-[#8b5a2b] shadow-xl rounded-lg p-10 
                w-full max-w-xl text-center backdrop-blur-sm">

        <?php if ($user): ?>
            <h1 class="text-3xl text-[#4b2e0f] mb-4">
                Profil de <span class="font-bold"><?= htmlspecialchars($user['username']) ?></span>
            </h1>

            <div class="text-lg text-[#4b2e0f] mb-2">
                <strong>Email :</strong>

                <?php if (empty($user['email'])): ?>
                    <a href="add_email.php?id=<?= $id ?>"
                        class="text-blue-700 underline hover:text-blue-900 ml-2">
                        Ajouter un email
                    </a>
                <?php else: ?>
                    <span class="ml-2"><?= htmlspecialchars($user['email']) ?></span>
                <?php endif; ?>
            </div>


            <p class="text-lg text-[#4b2e0f] mb-6">
                <strong>Inscription :</strong> <?= $user['created_at'] ?>
            </p>

        <?php else: ?>
            <h1 class="text-2xl text-red-700">Utilisateur introuvable</h1>
        <?php endif; ?>

        <a href="list_users.php"
            class="inline-block bg-[#8b5a2b] hover:bg-[#a66c3b] text-white font-bold py-3 px-6 
                  rounded transition transform hover:scale-105 mt-6">
            Retour à la liste
        </a>

    </div>

</body>

</html>