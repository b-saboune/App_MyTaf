<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Rapport</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../App_MyTaf/assets/css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header-container, .footer-container {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .add-project-container {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
        }

        select, textarea, input[type="file"], button {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        button {
            background-color: #6a11cb;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2575fc;
        }
    </style>
</head>
<body>
<header class="header-container">
    <h1>MYTAF - Ajouter un Rapport</h1>
</header>

<div class="add-project-container">
    <h1>Ajouter un rapport pour un projet</h1>
    <form action="../controllers/ReportController.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create">
        
        <label for="projet_id">Projet :</label>
        <select id="projet_id" name="projet_id">
            <option value="">Sélectionnez un projet (optionnel)</option>
            <?php
            require_once "../config/Database.php";
            require_once "../classes/Project.php";
            $database = new Database();
            $db = $database->getConnection();
            $project = new Project($db);
            $projects = $project->read();
            foreach ($projects as $projet): ?>
                <option value="<?= htmlspecialchars($projet['idprojet']) ?>">
                    <?= htmlspecialchars($projet['nom_projet']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="contenu">Contenu :</label>
        <textarea id="contenu" name="contenu" required></textarea>

        <label for="file">Importer un fichier (Word, Excel, PDF) :</label>
        <input type="file" id="file" name="file" accept=".doc,.docx,.xls,.xlsx,.pdf">

        <button type="submit">Créer</button>
    </form>
</div>

<footer class="footer-container">
    <p>&copy; 2025 MYTAF. Tous droits réservés.</p>
</footer>
</body>
</html>
