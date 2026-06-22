<?php
require_once 'config.php';

// Si le formulaire est soumis
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);

    if (!empty($email)) {

        // Vérifier si l'email existe
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Générer un token sécurisé
            $token = bin2hex(random_bytes(32));

            // Stocker le token en base
            $update = $pdo->prepare("UPDATE utilisateurs SET reset_token = ? WHERE email = ?");
            $update->execute([$token, $email]);

            // Lien de réinitialisation (affiché à l'écran)
            $reset_link = "reset_password.php?token=" . $token;

            $message = [
                "type" => "success",
                "text" => "Lien de réinitialisation généré : <br><a class='underline text-blue-700' href='$reset_link'>$reset_link</a>"
            ];

        } else {
            $message = [
                "type" => "error",
                "text" => "Aucun compte trouvé avec cet email."
            ];
        }
    } else {
        $message = [
            "type" => "error",
            "text" => "Veuillez entrer un email."
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
    <title>Mot de passe oublié</title>
</head>

<body class="min-h-screen bg-[url('https://images.unsplash.com/photo-1526401485004-2fda9f6d2f6b')] 
             bg-cover bg-center bg-fixed flex flex-col items-center justify-center">

    <div class="bg-[#fff8e6]/90 border-4 border-[#8b5a2b] shadow-xl rounded-lg p-10 
                w-full max-w-5xl text-center backdrop-blur-sm">

        <h1 class="text-3xl text-[#4b2e0f] mb-6">Mot de passe oublié</h1>

        <?php if ($message): ?>
            <div class="mb-4 px-4 py-3 rounded 
                        <?= $message['type'] === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700' ?>">
                <?= $message['text'] ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-4">

            <input type="email" name="email" placeholder="Votre email"
                   class="p-3 border rounded w-full focus:outline-none focus:ring-2 focus:ring-[#8b5a2b]">

            <button type="submit"
                    class="bg-[#8b5a2b] hover:bg-[#a66c3b] text-white font-bold py-3 px-6 rounded transition transform hover:scale-105">
                Générer un lien de réinitialisation
            </button>
        </form>

        <a href="connexion.php"
           class="inline-block mt-6 text-[#8b5a2b] underline hover:text-[#a66c3b]">
            Retour à la connexion
        </a>

    </div>

</body>
</html>
