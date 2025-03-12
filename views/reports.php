<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Afficher les messages de session
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']); // Supprimez le message après l'affichage
}
if (isset($_SESSION['error'])) {
    echo "<p>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']); // Supprimez l'erreur après l'affichage
}

require_once "../config/Database.php";
require_once "../classes/Report.php";
require_once "../classes/Project.php";

$database = new Database();
$db = $database->getConnection();
$report = new Report($db);
$project = new Project($db);

// Initialisation de $projet_id
$projet_id = isset($_GET['projet_id']) ? intval($_GET['projet_id']) : null;

// Récupérer les rapports avec le nom du projet
$reports = [];
if ($projet_id) {
    $reports = $report->readByProject($projet_id);
} else {
    // Si aucun projet n'est sélectionné, récupérer tous les rapports avec le nom du projet
    $reports = $report->readAllWithProjectName();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Rapports</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../App_MyTaf/assets/css/style.css">

    <style>
        /* Styles CSS identiques au premier exemple */
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

        .dashboard-content {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .dashboard-content h1 {
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
    </style>
</head>
<body>
<header>
    <div class="header-container">
        <div class="logo">MYTAF</div>
        <nav>
            <ul>
                <li>
                    <!-- Redirection dynamique en fonction du rôle -->
                    <a href="<?= ($_SESSION['user']['role'] === 'admin') ? '../views/admin_dashboard.php' : '../views/member_dashboard.php' ?>">
                        Tableau de bord
                    </a>
                </li>
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
    <section class="dashboard-content">
        <h1>Liste des Rapports</h1>
        <a class="btn btn-add" href="report_form.php?projet_id=<?= $projet_id ?>">Ajouter un rapport</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Contenu</th>
                    <th>Fichier</th>
                    <th>Projet</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?= htmlspecialchars($report['idrapport']) ?></td>
                            <td><?= htmlspecialchars($report['contenu']) ?></td>
                            <td>
                                <?php if ($report['file_path']): ?>
                                    <a href="<?= htmlspecialchars($report['file_path']) ?>" target="_blank">Télécharger</a>
                                <?php else: ?>
                                    Aucun fichier
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($report['nom_projet']): ?>
                                    <?= htmlspecialchars($report['nom_projet']) ?>
                                <?php else: ?>
                                    Aucun projet associé
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a class="btn btn-edit" href="report_edit.php?id=<?= htmlspecialchars($report['idrapport']) ?>">Modifier</a>
                                <form action="../controllers/ReportController.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($report['idrapport']) ?>">
                                    <button type="submit" class="btn btn-delete">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Aucun rapport disponible.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<footer>
    <div class="footer-container">
        <p>&copy; 2025 MYTAF. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>