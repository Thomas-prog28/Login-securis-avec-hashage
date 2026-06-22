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
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Police Western -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">

    <title>Dashboard</title>

    <style>
        body {
            font-family: "Cinzel", serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[url('https://images.unsplash.com/photo-1526401485004-2fda9f6d2f6b')] bg-cover bg-center bg-fixed flex flex-col items-center justify-center">

    <div class="bg-[#fff8e6]/90 border-4 border-[#8b5a2b] shadow-xl rounded-lg p-10 w-full max-w-xl text-center backdrop-blur-sm">

        <h1 class="text-4xl text-[#4b2e0f] drop-shadow-md mb-4">
            Dashboard des familles
        </h1>

        <h2 class="text-2xl text-[#4b2e0f] mb-8">
            Bonjour <span class="font-bold"><?= htmlspecialchars($_SESSION['name']) ?></span>
        </h2>

        <p class="block text-lg font-bold text-[#4b2e0f]">
            Site en construction
        </p>

        <a href="deconnexion.php"
            class="inline-block bg-[#8b5a2b] hover:bg-[#a66c3b] text-white font-bold py-3 px-6 rounded transition transform hover:scale-105">
            Se déconnecter
        </a>

    </div>

    <!-- Lien vers la liste des utilisateurs -->
   <div class="text-center mt-6">
    <a href="list_users.php"
       class="inline-flex items-center gap-2 bg-[#8b5a2b] hover:bg-[#a66c3b] text-white font-bold py-3 px-6 rounded transition transform hover:scale-105 shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-4a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        Vérifier les utilisateurs
    </a>
</div>




</body>

</html>