<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../config/Database.php";
require_once "../classes/Report.php";
require_once "../classes/Project.php";

$database = new Database();
$db = $database->getConnection();
$report = new Report($db);
$project = new Project($db);

// Récupérer l'ID du rapport à modifier
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($id) {
    $rapport = $report->readById($id);
} else {
    echo "ID du rapport non spécifié.";
    exit;
}

// Récupérer tous les projets pour la liste déroulante
$projects = $project->read();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Rapport</title>
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

        .form-section {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-section h1 {
            font-size: 2rem;
            color: #6a11cb;
            margin-bottom: 20px;
        }

        .form-section label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        .form-section input[type="text"],
        .form-section input[type="date"],
        .form-section input[type="number"],
        .form-section textarea,
        .form-section select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s ease;
        }

        .form-section input[type="text"]:focus,
        .form-section input[type="date"]:focus,
        .form-section input[type="number"]:focus,
        .form-section textarea:focus,
        .form-section select:focus {
            border-color: #6a11cb;
            outline: none;
        }

        .form-section textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-section button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: #6a11cb;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-section button:hover {
            background-color: #2575fc;
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
                <li><a href="../views/dashboard.php">Tableau de bord</a></li>
                <li><a href="../views/projects.php">Projets</a></li>
                <li><a href="../views/tasks.php">Tâches</a></li>
                <li><a href="../controllers/logout.php">Déconnexion</a></li>
            </ul>
        </nav>
        <div class="user-info">
            <?php if (isset($_SESSION['user'])): ?>
                <span class="user-name"><?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                (<?= htmlspecialchars($_SESSION['user']['role']) ?>)
            <?php endif; ?>
        </div>
    </div>
</header>

<main>
    <section class="form-section">
        <h1>Modifier un Rapport</h1>
        <form action="../controllers/ReportController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= htmlspecialchars($rapport['idrapport']) ?>">

            <label for="projet_id">Projet :</label>
            <select id="projet_id" name="projet_id">
                <option value="">Sélectionnez un projet (optionnel)</option>
                <?php foreach ($projects as $projet): ?>
                    <option value="<?= htmlspecialchars($projet['idprojet']) ?>" <?= $projet['idprojet'] == $rapport['projet_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($projet['nom_projet']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label for="contenu">Contenu :</label>
            <textarea id="contenu" name="contenu" required><?= htmlspecialchars($rapport['contenu']) ?></textarea>
            
            <label for="file">Importer un fichier (Word, Excel, PDF) :</label>
            <input type="file" id="file" name="file" accept=".doc,.docx,.xls,.xlsx,.pdf">
            
            <button type="submit" class="btn btn-edit">Mettre à jour</button>
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