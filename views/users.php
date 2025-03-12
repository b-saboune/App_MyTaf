<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../controllers/AuthMiddleware.php';
require_once '../classes/User.php';
require_once '../config/Database.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$users = $user->readAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les utilisateurs</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../App_MyTaf/assets/css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header-container {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-radius: 0 0 15px 15px;
        }

        .header-container .logo {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .header-container nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .header-container nav ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        .header-container nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-section {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .user-section h1 {
            font-size: 2rem;
            color: #6a11cb;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: #6a11cb;
            border-radius: 10px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2575fc;
        }

        .btn-add {
            background-color: #28a745;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-edit {
            background-color: #ffc107;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #6a11cb;
            color: #fff;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .actions a {
            margin-right: 10px;
            padding: 8px 12px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .footer-container {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: auto;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        .contact-info a {
            color: #fff;
            text-decoration: none;
        }

        .social-media img {
            width: 24px;
            height: 24px;
            margin: 0 10px;
        }
    </style>
</head>
<body>
<header>
    <div class="header-container">
        <div class="logo">MYTAF</div>
        <nav>
            <ul>
                <li><a href="../views/admin_dashboard.php">Tableau de bord</a></li>
                <li><a href="../views/projects.php">Projets</a></li>
                <li><a href="../views/tasks.php">Tâches</a></li>
                <li><a href="../controllers/logout.php">Déconnexion</a></li>
            </ul>
        </nav>
        <div class="user-info">
            <?php
            if (isset($_SESSION['user'])) {
                echo '<span class="user-name">' . htmlspecialchars($_SESSION['user']['username']) . '</span> (' . htmlspecialchars($_SESSION['user']['role']) . ')';
            }
            ?>
        </div>
    </div>
</header>

<main>
    <section class="user-section">
        <h1>Liste des utilisateurs</h1>
        
        <a class="btn btn-add" href="register.php">Ajouter un utilisateur</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['idUtilisateurs'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($u['username'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($u['email'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($u['role'] ?? 'Utilisateur') ?></td>
                        <td class="actions">
                            <a class="btn btn-edit" href="edit_user.php?id=<?= htmlspecialchars($u['idUtilisateurs'] ?? '') ?>">Modifier</a>
                            <a class="btn btn-delete" href="../controllers/UserController.php?action=delete&id=<?= htmlspecialchars($u['idUtilisateurs'] ?? '') ?>" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<footer>
    <div class="footer-container">
        <div class="footer-links">
            <a href="../views/dashboard.php">Tableau de bord</a>
            <a href="../views/projects.php">Projets</a>
            <a href="../views/tasks.php">Tâches</a>
        </div>
        <div class="contact-info">
            <p>Contact : <a href="mailto:support@mytaf.com">support@mytaf.com</a></p>
        </div>
        <div class="social-media">
            <a href="#"><img src="../assets/img/facebook-icon.png" alt="Facebook"></a>
            <a href="#"><img src="../assets/img/twitter-icon.png" alt="Twitter"></a>
        </div>
    </div>
</footer>
</body>
</html>