<?php

if (session_start() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$dbname = "tploginphp";
$user = "root";
$password ="";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false, 
    ]);
    // echo "connecté";
} catch(PDOException $e) {
    die("Erreur critique de connexion : " . $e->getMessage());
}
?>