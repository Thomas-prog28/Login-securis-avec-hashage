<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Seul un administrateur peut supprimer un utilisateur
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: list_users.php?error=forbidden");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list_users.php?error=not_found");
    exit;
}

$id = (int) $_GET['id'];

// Empêcher de se supprimer soi-même
if ($id === (int) $_SESSION['user_id']) {
    header("Location: list_users.php?error=cannot_delete_self");
    exit;
}

// Vérifier que l'utilisateur existe
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
if (!$stmt->fetch()) {
    header("Location: list_users.php?error=not_found");
    exit;
}

// Suppression
$delete = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
$delete->execute([$id]);

header("Location: list_users.php?msg=deleted");
exit;
