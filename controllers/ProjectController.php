<?php
session_start();
require_once "../config/Database.php";
require_once "../classes/Project.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../views/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$project = new Project($db);

// Récupérer les projets en fonction du rôle de l'utilisateur
if ($_SESSION['user']['role'] === 'admin') {
    $projects = $project->read();
} else {
    $projects = $project->readByUser($_SESSION['user']['idUtilisateurs']);
}

// Handle actions
define("ACTIONS", ["create", "update", "delete"]);

if (isset($_POST['action']) && in_array($_POST['action'], ACTIONS)) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            if ($_SESSION['user']['role'] !== 'admin') {
                header("Location: ../views/projects.php?error=Accès refusé.");
                exit();
            }
            // Ensure all required fields are provided
            if (isset($_POST['nom'], $_POST['description'], $_POST['date_debut'], $_POST['date_fin'], $_POST['statut'])) {
                $nom = trim($_POST['nom']);
                $description = trim($_POST['description']);
                $date_debut = $_POST['date_debut'];
                $date_fin = $_POST['date_fin'];
                $statut = $_POST['statut'];

                if ($project->create($nom, $description, $date_debut, $date_fin, $statut)) {
                    header("Location: ../views/projects.php?success=Projet créé avec succès.");
                } else {
                    header("Location: ../views/projects.php?error=Erreur lors de la création du projet.");
                }
            } else {
                header("Location: ../views/projects.php?error=Tous les champs sont requis pour créer un projet.");
            }
            break;

        case 'update':
            if ($_SESSION['user']['role'] !== 'admin') {
                header("Location: ../views/projects.php?error=Accès refusé.");
                exit();
            }
            if (isset($_POST['id'], $_POST['nom'], $_POST['description'], $_POST['date_debut'], $_POST['date_fin'], $_POST['statut'])) {
                $id = intval($_POST['id']);
                $nom = trim($_POST['nom']);
                $description = trim($_POST['description']);
                $date_debut = $_POST['date_debut'];
                $date_fin = $_POST['date_fin'];
                $statut = $_POST['statut'];

                if ($project->update($id, $nom, $description, $date_debut, $date_fin, $statut)) {
                    header("Location: ../views/projects.php?success=Projet mis à jour avec succès.");
                } else {
                    header("Location: ../views/projects.php?error=Erreur lors de la mise à jour du projet.");
                }
            } else {
                header("Location: ../views/projects.php?error=Tous les champs sont requis pour modifier un projet.");
            }
            break;

        case 'delete':
            if ($_SESSION['user']['role'] !== 'admin') {
                header("Location: ../views/projects.php?error=Accès refusé.");
                exit();
            }
            if (isset($_POST['id'])) {
                $id = intval($_POST['id']);

                if ($project->delete($id)) {
                    header("Location: ../views/projects.php?success=Projet supprimé avec succès.");
                } else {
                    header("Location: ../views/projects.php?error=Erreur lors de la suppression du projet.");
                }
            } else {
                header("Location: ../views/projects.php?error=ID du projet requis pour supprimer.");
            }
            break;

        default:
            header("Location: ../views/projects.php?error=Action inconnue.");
            break;
    }
} else {
    header("Location: ../views/projects.php?error=Aucune action valide spécifiée.");
}
?>