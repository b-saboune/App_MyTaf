<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../views/login.php");
    exit();
}

// Vérifier si l'ID du projet est fourni
if (!isset($_GET['id'])) {
    header("Location: ../views/projects.php");
    exit();
}

$projectId = intval($_GET['id']);

// Afficher la page de confirmation
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmer la suppression</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../App_MyTaf/assets/css/style.css">

</head>
<body>
<header>
    <div class="header-container">
        <div class="logo">MYTAF</div>
        <nav>
            <ul>
                <li><a href="../views/dashboard.php">Tableau de bord</a></li>
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
    <section class="confirmation-section">
        <h1>Confirmer la suppression</h1>
        <p>Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.</p>
        <form action="../controllers/ProjectController.php" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $projectId ?>">
            <button type="submit" class="btn btn-delete">Confirmer la suppression</button>
            <a href="../views/projects.php" class="btn btn-cancel">Annuler</a>
        </form>
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