<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config/Database.php';
require_once '../classes/Task.php';
require_once '../classes/Project.php';
require_once '../classes/User.php';

$database = new Database();
$db = $database->getConnection();
$task = new Task($db);
$project = new Project($db);
$user = new User($db);

// Initialisation des variables avec des valeurs par défaut
$titre = '';
$description = '';
$statut = 'À faire';
$date_limite = '';
$Utilisateurs_id = '';
$projet_id = '';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $task->idtaches = $_GET['id'];
    $stmt = $task->readById();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $titre = $row['titre'];
        $description = $row['description'];
        $statut = $row['statut'];
        $date_limite = $row['date_limite'];
        $Utilisateurs_id = $row['Utilisateurs_id'];
        $projet_id = $row['projet_id'];
    } else {
        echo "Tâche non trouvée.";
        exit;
    }
}

// Récupérer la liste des utilisateurs et des projets
$projects = $project->readAll();
$users = $user->readAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task->idtaches = $_POST['idtaches'];
    $task->titre = $_POST['titre'];
    $task->description = $_POST['description'];
    $task->statut = $_POST['statut'];
    $task->date_limite = $_POST['date_limite'];
    $task->Utilisateurs_id = !empty($_POST['Utilisateurs_id']) ? $_POST['Utilisateurs_id'] : null; // Champ optionnel
    $task->projet_id = !empty($_POST['projet_id']) ? $_POST['projet_id'] : null; // Champ optionnel

    if ($task->update()) {
        header("Location: tasks.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour de la tâche.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Tâche</title>
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
            <?php
            if (isset($_SESSION['user'])) {
                echo '<span class="user-name">' . htmlspecialchars($_SESSION['user']['username']) . '</span> (' . htmlspecialchars($_SESSION['user']['role']) . ')';
            }
            ?>
        </div>
    </div>
</header>

<main>
    <section class="form-section">
        <h1>Modifier une Tâche</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="idtaches" value="<?php echo $task->idtaches; ?>">
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" value="<?php echo $titre; ?>" required><br><br>
            <label for="description">Description :</label>
            <textarea id="description" name="description"><?php echo $description; ?></textarea><br><br>
            <label for="statut">Statut :</label>
            <select id="statut" name="statut">
                <option value="À faire" <?php echo ($statut == 'À faire') ? 'selected' : ''; ?>>À faire</option>
                <option value="En cours" <?php echo ($statut == 'En cours') ? 'selected' : ''; ?>>En cours</option>
                <option value="Terminé" <?php echo ($statut == 'Terminé') ? 'selected' : ''; ?>>Terminé</option>
            </select><br><br>
            <label for="date_limite">Date limite :</label>
            <input type="date" id="date_limite" name="date_limite" value="<?php echo $date_limite; ?>" required><br><br>

            <!-- Liste déroulante pour les utilisateurs -->
            <label for="Utilisateurs_id">Utilisateur :</label>
            <select id="Utilisateurs_id" name="Utilisateurs_id">
                <option value="">Aucun utilisateur</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['idUtilisateurs'] ?>" <?= $Utilisateurs_id == $user['idUtilisateurs'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user['username']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <!-- Liste déroulante pour les projets -->
            <label for="projet_id">Projet :</label>
            <select id="projet_id" name="projet_id">
                <option value="">Aucun projet</option>
                <?php foreach ($projects as $project): ?>
                    <option value="<?= $project['idprojet'] ?>" <?= $projet_id == $project['idprojet'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($project['nom_projet']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <button type="submit">Mettre à jour</button>
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