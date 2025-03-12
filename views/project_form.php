<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../views/login.php");
    exit();
}

require_once "../config/Database.php";
require_once "../classes/Project.php";

$database = new Database();
$db = $database->getConnection();
$project = new Project($db);

$projectId = isset($_GET['id']) ? intval($_GET['id']) : null;
$currentProject = null;

if ($projectId) {
    $currentProject = $project->readById($projectId);
    if (!$currentProject) {
        header("Location: ../views/projects.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $projectId ? 'Modifier' : 'Ajouter' ?> un projet</title>
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

    .project-section {
        max-width: 1200px;
        margin: 40px auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .project-section h1 {
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
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn:hover {
        background-color: #2575fc;
        transform: translateY(-2px);
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

    /* Styles pour les champs de formulaire */
    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .block {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #555;
    }

    .w-full {
        width: 100%;
    }

    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .mt-1 {
        margin-top: 0.25rem;
    }

    .border {
        border: 1px solid #ddd;
    }

    .rounded-md {
        border-radius: 0.375rem;
    }

    .focus\:outline-none:focus {
        outline: none;
    }

    .focus\:ring-2:focus {
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
    }

    .focus\:ring-indigo-500:focus {
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
    }

    .transition {
        transition: all 0.3s ease;
    }

    .grid {
        display: grid;
    }

    .grid-cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .gap-4 {
        gap: 1rem;
    }

    .flex {
        display: flex;
    }

    .justify-between {
        justify-content: space-between;
    }

    .text-gray-600 {
        color: #666;
    }

    .text-indigo-500 {
        color: #6a11cb;
    }

    .hover\:bg-indigo-600:hover {
        background-color: #2575fc;
    }

    .hover\:transform:hover {
        transform: translateY(-2px);
    }
</style>
      
        </div>
    </div>
</header>

<main>
    <section class="project-section">
        <h1><?= $projectId ? 'Modifier' : 'Ajouter' ?> un projet</h1>
        <form action="../controllers/ProjectController.php" method="POST">
            <input type="hidden" name="action" value="<?= $projectId ? 'update' : 'create' ?>">
            <?php if ($projectId): ?>
                <input type="hidden" name="id" value="<?= $projectId ?>">
            <?php endif; ?>

            <div class="mb-4">
                <label for="nom" class="block text-gray-600">Nom du projet</label>
                <input type="text" id="nom" name="nom" value="<?= $currentProject ? htmlspecialchars($currentProject['nom_projet']) : '' ?>" required
                    class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-600">Description</label>
                <textarea id="description" name="description" required
                    class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"><?= $currentProject ? htmlspecialchars($currentProject['description']) : '' ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="date_debut" class="block text-gray-600">Date de début</label>
                    <input type="date" id="date_debut" name="date_debut" value="<?= $currentProject ? htmlspecialchars($currentProject['date_debut']) : '' ?>" required
                        class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="date_fin" class="block text-gray-600">Date de fin</label>
                    <input type="date" id="date_fin" name="date_fin" value="<?= $currentProject ? htmlspecialchars($currentProject['date_fin']) : '' ?>" required
                        class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="mb-4">
                <label for="statut" class="block text-gray-600">Statut</label>
                <select id="statut" name="statut" required
                    class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="en cours" <?= $currentProject && $currentProject['statut'] === 'en cours' ? 'selected' : '' ?>>En cours</option>
                    <option value="terminé" <?= $currentProject && $currentProject['statut'] === 'terminé' ? 'selected' : '' ?>>Terminé</option>
                    <option value="en attente" <?= $currentProject && $currentProject['statut'] === 'en attente' ? 'selected' : '' ?>>En attente</option>
                </select>
            </div>

            <div class="flex justify-between">
                <button type="submit" class="btn">
                    <?= $projectId ? 'Mettre à jour' : 'Créer' ?>
                </button>
                <a href="../views/projects.php" class="btn btn-delete">
                    Annuler
                </a>
            </div>
        </form>
    </section>
</main>

<footer>
    <div class="footer-container">
        <p>&copy; 2025 MYTAF. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>