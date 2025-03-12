<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../views/login.php");
    exit();
}

require_once '../config/Database.php';
require_once '../classes/Task.php';

$database = new Database();
$db = $database->getConnection();

$task = new Task($db);

$taskId = $_GET['id'] ?? null;
if (!$taskId) {
    header("Location: ../views/tasks.php?error=1"); // Rediriger vers tasks.php avec un message d'erreur
    exit();
}

$task->idtaches = $taskId;
if ($task->delete()) {
    header("Location: ../views/tasks.php?success=3"); // Rediriger vers tasks.php avec un message de succès
    exit();
} else {
    header("Location: ../views/tasks.php?error=1"); // Rediriger vers tasks.php avec un message d'erreur
    exit();
}
?>