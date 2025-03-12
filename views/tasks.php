<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../views/login.php");
    exit();
}

// Afficher un message de succès ou d'erreur
if (isset($_GET['success'])) {
    $message = match ($_GET['success']) {
        1 => "Tâche créée avec succès !",
        2 => "Tâche mise à jour avec succès !",
        3 => "Tâche supprimée avec succès !",
        default => ""
    };
    if (!empty($message)) {
        echo "<div class='alert alert-success'>$message</div>";
    }
} elseif (isset($_GET['error'])) {
    $message = match ($_GET['error']) {
        1 => "Une erreur s'est produite lors de la suppression de la tâche.",
        default => "Une erreur inconnue s'est produite."
    };
    if (!empty($message)) {
        echo "<div class='alert alert-danger'>$message</div>";
    }
}

require_once '../config/Database.php';
require_once '../classes/Task.php';
require_once '../classes/Project.php';

$database = new Database();
$db = $database->getConnection();

$task = new Task($db);
$project = new Project($db);

// Récupérer tous les projets
if ($_SESSION['user']['role'] === 'admin') {
    $projects = $project->read();
} else {
    $projects = $project->readByUser($_SESSION['user']['idUtilisateurs']);
}
?>

<!-- Le reste du code HTML reste inchangé -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Projets et Tâches</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../App_MyTaf/assets/css/style.css">

   <style>
        /* Ajoutez le CSS ici */
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

        .task-section {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .task-section h1 {
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

        .footer-links {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        .contact-info {
            margin-bottom: 10px;
        }

        .contact-info a {
            color: #fff;
            text-decoration: none;
        }

        .social-media img {
            width: 24px;
            height: 24px;
        }
    </style>
</head>
<body>
<header>
    <div class="header-container">
        <div class="logo">MYTAF</div>
        <nav>
            <ul>
                <li><a href="../views/projects.php">Gestion des projets</a></li>
                <li><a href="../views/tasks.php">Gestion des tâches</a></li>
                <li><a href="../views/reports.php">Gestion des rapports</a></li>
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
    <section class="task-section">
        <h1>Liste des Projets et Tâches</h1>
        <a class="btn btn-add" href="task_form.php">Ajouter une tâche</a>
        <?php if ($projects): ?>
            <?php foreach ($projects as $project_row): ?>
                <h2>Projet : <?= htmlspecialchars($project_row['nom_projet']) ?></h2>
                <p>Description : <?= htmlspecialchars($project_row['description']) ?></p>
                <?php
                // Récupérer les tâches pour ce projet
                $stmt = $task->readByProject($project_row['idprojet']);
                $num = $stmt->rowCount();
                ?>

                <?php if ($num > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Date Limite</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['idtaches']) ?></td>
                                    <td><?= htmlspecialchars($row['titre']) ?></td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td><?= htmlspecialchars($row['statut']) ?></td>
                                    <td><?= htmlspecialchars($row['date_limite']) ?></td>
                                    <td>
                                        <a class="btn btn-edit" href="edit_task.php?id=<?= $row['idtaches'] ?>">Modifier</a>
                                        <a class="btn btn-delete" href="delete_task.php?id=<?= $row['idtaches'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucune tâche trouvée pour ce projet.</p>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun projet trouvé.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <div class="footer-container">
        <p>&copy; 2025 MYTAF. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>