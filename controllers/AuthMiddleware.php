<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../views/login.php");
    exit();
}

// Vérifier que l'utilisateur est un admin pour certaines actions
if ($_SESSION['user']['role'] !== 'admin') {
    echo "Accès refusé.";
    exit();
}
?>