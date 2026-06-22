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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Police Western -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Cinzel", serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[url('https://images.unsplash.com/photo-1526401485004-2fda9f6d2f6b')] bg-cover bg-center bg-fixed flex items-center justify-center">

    <div class="bg-[#fff8e6]/90 border-4 border-[#8b5a2b] shadow-xl rounded-lg p-10 w-full max-w-md backdrop-blur-sm">

        <h1 class="text-4xl text-center text-[#4b2e0f] drop-shadow-md mb-2">Dashboard des familles</h1>
        <h2 class="text-2xl text-center text-[#4b2e0f] mb-8">Connexion</h2>
        <div class="text-center mb-6">
            <a href="inscription.php" class="text-[#8b5a2b] font-bold underline hover:text-[#a66c3b]">
                Pas encore inscrit ? Enregistre-toi
            </a>
        </div>
<?php if (!empty($_SESSION['success_message'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['success_message']) ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

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

            <div class="text-right mb-6">
            <a href="forgot_password.php" class="text-[#8b5a2b] font-bold underline hover:text-[#a66c3b]">
                Mot de passe oublié ?
            </a>
        </div>

            <!-- Zone d'erreurs -->
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div>
                <input type="submit" value="Se connecter"
                    class="w-full bg-[#8b5a2b] hover:bg-[#a66c3b] text-white font-bold py-3 rounded cursor-pointer transition transform hover:scale-105">
            </div>

        </form>
    </div>

</body>

</html>