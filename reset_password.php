<?php
require_once 'config.php';

$message = null;

// Vérifier que le token est présent dans l'URL
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Token manquant.");
}

$token = $_GET['token'];

// Vérifier si le token existe en base
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE reset_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("Token invalide ou expiré.");
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = $_POST['password'] ?? "";
    $confirm = $_POST['confirm'] ?? "";

    if (strlen($password) < 12) {
        $message = [
            "type" => "error",
            "text" => "Le mot de passe doit contenir au moins 12 caractères."
        ];
    } elseif ($password !== $confirm) {
        $message = [
            "type" => "error",
            "text" => "Les mots de passe ne correspondent pas."
        ];
    } else {
        // Hash du mot de passe
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Mise à jour du mot de passe + suppression du token
        $update = $pdo->prepare("UPDATE utilisateurs SET password = ?, reset_token = NULL WHERE reset_token = ?");
        $update->execute([$hashed, $token]);

        $message = [
            "type" => "success",
            "text" => "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter."
        ];
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
    <title>Réinitialisation du mot de passe</title>
</head>

<body class="min-h-screen bg-[url('https://images.unsplash.com/photo-1526401485004-2fda9f6d2f6b')] 
             bg-cover bg-center bg-fixed flex flex-col items-center justify-center">

    <div class="bg-[#fff8e6]/90 border-4 border-[#8b5a2b] shadow-xl rounded-lg p-10 
                w-full max-w-5xl text-center backdrop-blur-sm">

        <h1 class="text-3xl text-[#4b2e0f] mb-6">Réinitialiser votre mot de passe</h1>

        <?php if ($message): ?>
            <div class="mb-4 px-4 py-3 rounded 
                        <?= $message['type'] === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700' ?>">
                <?= $message['text'] ?>
            </div>
        <?php endif; ?>

        <?php if (!$message || $message['type'] !== 'success'): ?>
        <form method="POST" class="flex flex-col gap-4">

            <input type="password" name="password" placeholder="Nouveau mot de passe"
                   class="p-3 border rounded w-full focus:outline-none focus:ring-2 focus:ring-[#8b5a2b]" required>

            <input type="password" name="confirm" placeholder="Confirmer le mot de passe"
                   class="p-3 border rounded w-full focus:outline-none focus:ring-2 focus:ring-[#8b5a2b]" required>

            <button type="submit"
                    class="bg-[#8b5a2b] hover:bg-[#a66c3b] text-white font-bold py-3 px-6 rounded transition transform hover:scale-105">
                Réinitialiser le mot de passe
            </button>
        </form>
        <?php endif; ?>

        <a href="connexion.php"
           class="inline-block mt-6 text-[#8b5a2b] underline hover:text-[#a66c3b]">
            Retour à la connexion
        </a>

    </div>

</body>
</html>
