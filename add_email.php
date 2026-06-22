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

$id = (int) $_GET['id'];

// Si le formulaire est soumis
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_POST["email"])) {

        $email = trim($_POST["email"]);

        // Validation simple
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Adresse email invalide.";
        } else {
            // Mise à jour en BDD
            $stmt = $pdo->prepare("UPDATE utilisateurs SET email = ? WHERE id = ?");
            $stmt->execute([$email, $id]);

            // Redirection vers le profil
            header("Location: profile.php?id=" . $id);
            exit;
        }
    } else {
        $message = "Veuillez entrer un email.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
    <style> body { font-family: "Cinzel", serif; } </style>
    <title>Ajouter un email</title>
</head>

<body class="min-h-screen bg-[url('https://images.unsplash.com/photo-1526401485004-2fda9f6d2f6b')] 
             bg-cover bg-center bg-fixed flex flex-col items-center justify-center">

    <div class="bg-[#fff8e6]/90 border-4 border-[#8b5a2b] shadow-xl rounded-lg p-10 
                w-full max-w-xl text-center backdrop-blur-sm">

        <h1 class="text-3xl text-[#4b2e0f] mb-6">Ajouter un email</h1>

        <?php if (!empty($message)): ?>
            <p class="text-red-700 font-bold mb-4"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-4">

            <input type="email" name="email" placeholder="Entrez un email"
                   class="p-3 border-2 border-[#8b5a2b] rounded bg-white text-[#4b2e0f] focus:outline-none">

            <button type="submit"
                    class="bg-[#8b5a2b] hover:bg-[#a66c3b] text-white font-bold py-3 px-6 rounded 
                           transition transform hover:scale-105">
                Enregistrer
            </button>
        </form>

        <a href="profile.php?id=<?= $id ?>"
           class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 
                  rounded transition transform hover:scale-105 mt-6">
            Annuler
        </a>

    </div>

</body>
</html>
