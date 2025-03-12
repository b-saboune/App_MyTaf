<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// ...
require_once '../config/Database.php';
require_once '../classes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register_admin') {
        $username = $_POST['username'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$username || !$email || !$password) {
            die("Erreur : Tous les champs sont obligatoires !");
        }

        // Hacher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Rôle admin
        $role = 'admin';

        // Insérer l'utilisateur dans la base de données
        $query = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            header("Location: ../views/login.php?message=Administrateur enregistré avec succès");
            exit();
        } else {
            echo "Erreur lors de l'inscription de l'administrateur.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        // Vérifier que seul un admin peut ajouter des utilisateurs
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Accès refusé : Seul un administrateur peut ajouter des utilisateurs.";
            exit();
        }

        $username = $_POST['username'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$username || !$email || !$password) {
            die("Erreur : Tous les champs sont obligatoires !");
        }

        if ($user->register($username, $email, $password)) {
            header("Location: ../views/users.php?message=Utilisateur enregistré avec succès");
            exit();
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
}

if ($action === 'login') {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        die("Erreur : Tous les champs sont obligatoires !");
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        if ($loggedInUser['role'] === 'admin' || $loggedInUser['role'] === 'member') {
            $_SESSION['user'] = $loggedInUser;

            if ($loggedInUser['role'] === 'admin') {
                header("Location: ../views/admin_dashboard.php");
            } elseif ($loggedInUser['role'] === 'member') {
                header("Location: ../views/member_dashboard.php");
            }
            exit();
        } else {
            // Refuser la connexion si le rôle n'est pas admin ou member
            header("Location: ../views/login.php?error=Rôle non autorisé");
            exit();
        }
    } else {
        header("Location: ../views/login.php?error=Email ou mot de passe incorrect");
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'delete') {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo "Accès refusé.";
            exit();
        }
        $id = $_GET['id'];
        if ($user->delete($id)) {
            header("Location: ../views/users.php?message=Utilisateur supprimé avec succès");
        } else {
            echo "Erreur lors de la suppression.";
        }
    }
}

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../views/login.php");
    exit();
}
?>