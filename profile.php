<?php
require_once 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

// --- Gestion de l'ajout d'avatar ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {

    $id = (int) $_POST['id'];
    $file = $_FILES['avatar'];

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (in_array($ext, $allowed) && $file['error'] === UPLOAD_ERR_OK) {

        $avatarName = uniqid("avatar_") . "." . $ext;
        $uploadPath = __DIR__ . "/uploads/" . $avatarName;

        move_uploaded_file($file['tmp_name'], $uploadPath);

        // Mise à jour BDD
        $stmt = $pdo->prepare("UPDATE utilisateurs SET avatar = ? WHERE id = ?");
        $stmt->execute([$avatarName, $id]);

        // Recharge la page
        header("Location: profil.php?id=" . $id);
        exit;
    }
}

// Vérifier que l'ID est présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID manquant.");
}

// Sécuriser l'ID
$id = (int) $_GET['id'];

// Récupérer l'utilisateur correspondant
$stmt = $pdo->prepare("SELECT username, email, created_at, avatar FROM utilisateurs WHERE id = ?");
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

            <!-- 🔥 AVATAR AJOUTÉ ICI -->
            <?php if (!empty($user['avatar'])): ?>

                <div class="relative group flex justify-center mb-6">

                    <!-- Avatar -->
                    <img src="uploads/<?= htmlspecialchars($user['avatar']) ?>"
                        alt="Avatar"
                        class="w-40 h-40 rounded-full object-cover border-4 border-[#8b5a2b] shadow-lg">

                    <!-- Cercle flouté au survol -->
                    <label for="changeAvatarInput"
                        class="absolute inset-0 flex items-center justify-center cursor-pointer">

                        <!-- Cercle flouté -->
                        <div class="opacity-0 group-hover:opacity-100 transition 
                        absolute w-48 h-48 rounded-full 
                        backdrop-blur-xl bg-black/30 shadow-xl">
                        </div>

                        <!-- Icône appareil photo -->
                        <span class="opacity-0 group-hover:opacity-100 transition 
                         text-white text-4xl z-10">
                            📷
                        </span>

                    </label>

                    
                    <!-- Input caché + auto-submit -->
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="file" name="avatar" id="changeAvatarInput" class="hidden"
                            accept="image/*" onchange="this.form.submit()">
                    </form>

                </div>




            <?php else: ?>

                <!-- Si pas d'avatar → bouton + (déjà OK dans ton code) -->
                <div class="flex flex-col items-center mb-6">

                    <p class="text-[#4b2e0f] italic mb-3">Aucun avatar</p>

                    <label for="addAvatarInput"
                        class="cursor-pointer flex items-center justify-center w-20 h-20 
                      rounded-full border-4 border-[#8b5a2b] bg-[#fff8e6] 
                      hover:bg-[#f0e0c0] transition shadow-lg">
                        <span class="text-4xl text-[#8b5a2b] font-bold">+</span>
                    </label>

                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="file" name="avatar" id="addAvatarInput" class="hidden"
                            accept="image/*" onchange="this.form.submit()">
                    </form>

                </div>

            <?php endif; ?>


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