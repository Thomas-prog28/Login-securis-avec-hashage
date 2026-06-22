<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

// Récupération des utilisateurs
$stmt = $pdo->query("SELECT id, username, email, created_at FROM utilisateurs ORDER BY id ASC");
$users = $stmt->fetchAll();
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
    <title>Liste des utilisateurs</title>
</head>

<body class="min-h-screen bg-[url('https://images.unsplash.com/photo-1526401485004-2fda9f6d2f6b')] bg-cover bg-center bg-fixed flex items-center justify-center">

    <div class="bg-[#fff8e6]/90 border-4 border-[#8b5a2b] shadow-xl rounded-lg p-10 w-full max-w-5xl backdrop-blur-sm">

        <h1 class="text-3xl text-[#4b2e0f] mb-6 text-center">Liste des utilisateurs</h1>

        <!-- Message éventuel -->
        <?php if (!empty($_GET['msg']) && $_GET['msg'] === "deleted"): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                Utilisateur supprimé avec succès.
            </div>
        <?php endif; ?>

        <?php if (!empty($_GET['error']) && $_GET['error'] === "cannot_delete_self"): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                L'utilisateur ne peut se supprimer lui-même.
            </div>
        <?php endif; ?>

        <?php if (!empty($_GET['error']) && $_GET['error'] === "not_found"): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                Utilisateur introuvable.
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse table-auto whitespace-nowrap">
                <tr class="bg-[#8b5a2b] text-white">
                    <th class="p-3 border">ID</th>
                    <th class="p-3 border">Nom d'utilisateur</th>
                    <th class="p-3 border">E-mail</th>
                    <th class="p-3 border">Inscription</th>
                    <th class="p-3 border">Actions</th>
                </tr>

                <?php foreach ($users as $u): ?>
                    <tr class="bg-[#fff8e6]">
                        <td class="border p-2"><?= $u['id'] ?></td>
                        <td class="border p-2"><?= htmlspecialchars($u['username']) ?></td>
                        <td class="border p-2"><?= htmlspecialchars($u['email']) ?></td>
                        <td class="border p-2"><?= $u['created_at'] ?></td>
                        <td class="border p-2 space-x-3">
                            <a class="text-blue-700 underline" href="profile.php?id=<?= $u['id'] ?>">Voir profil</a>
                            <a class="text-red-700 underline" href="delete.php?id=<?= $u['id'] ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="text-center mt-6">
            <a href="dashboard.php" class="text-[#8b5a2b] underline font-bold">Retour Dashboard</a>
        </div>

    </div>
</body>

</html>