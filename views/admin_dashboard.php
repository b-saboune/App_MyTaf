<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: ../views/login.php");
    exit();
}

// Vérifier que l'utilisateur est un admin
if ($_SESSION['user']['role'] !== 'admin') {
    echo "Accès refusé : Vous n'êtes pas autorisé à accéder à cette page.";
    exit();
}

// Connexion à la base de données
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Project.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Task.php';

$database = new Database();
$db = $database->getConnection();

$project = new Project($db);
$user = new User($db);
$task = new Task($db);

// Récupérer les critères de recherche et de tri
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'all';

// Récupérer les projets, tâches et utilisateurs en fonction des critères
$projects = $project->read();
$users = $user->readAll();
$tasks = $task->readAll();

// Filtrer les tâches en fonction du tri
if ($sort === 'en_cours') {
    $tasks = array_filter($tasks->fetchAll(PDO::FETCH_ASSOC), function($task) {
        return $task['statut'] === 'en_cours';
    });
} elseif ($sort === 'a_faire') {
    $tasks = array_filter($tasks->fetchAll(PDO::FETCH_ASSOC), function($task) {
        return $task['statut'] === 'a_faire';
    });
} elseif ($sort === 'termine') {
    $tasks = array_filter($tasks->fetchAll(PDO::FETCH_ASSOC), function($task) {
        return $task['statut'] === 'termine';
    });
} elseif ($sort === 'prioritaire') {
    $tasks = array_filter($tasks->fetchAll(PDO::FETCH_ASSOC), function($task) {
        return strtotime($task['date_limite']) < strtotime('+3 days');
    });
} else {
    $tasks = $tasks->fetchAll(PDO::FETCH_ASSOC);
}

// Filtrer les résultats en fonction de la recherche
if (!empty($search)) {
    $projects = array_filter($projects, function($project) use ($search) {
        return stripos($project['nom_projet'], $search) !== false;
    });

    $users = array_filter($users, function($user) use ($search) {
        return stripos($user['username'], $search) !== false;
    });

    $tasks = array_filter($tasks, function($task) use ($search) {
        return stripos($task['titre'], $search) !== false;
    });
}

// Organiser les données
$data = [];
foreach ($projects as $project) {
    $projectId = $project['idprojet'];
    $data[$projectId] = [
        'projet' => $project,
        'utilisateurs' => []
    ];

    // Associer les utilisateurs au projet
    foreach ($users as $user) {
        $userId = $user['idUtilisateurs'];
        $data[$projectId]['utilisateurs'][$userId] = [
            'utilisateur' => $user,
            'taches' => []
        ];

        // Associer les tâches à l'utilisateur et au projet
        foreach ($tasks as $task) {
            if ($task['projet_id'] == $projectId && $task['Utilisateurs_id'] == $userId) {
                $data[$projectId]['utilisateurs'][$userId]['taches'][] = $task;
            }
        }
    }
}

// Compter les tâches par statut
$taskStatusCount = [
    'en_cours' => 0,
    'a_faire' => 0,
    'termine' => 0
];

foreach ($tasks as $task) {
    if (isset($taskStatusCount[$task['statut']])) {
        $taskStatusCount[$task['statut']]++;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../App_MyTaf/assets/css/style.css">
    <style>
        /* Styles généraux */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* En-tête */
        .dashboard-header {
            padding: 20px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 0 0 15px 15px;
        }

        .dashboard-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .dashboard-header nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .dashboard-header nav ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: 500;
            padding: 10px 10px;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        .dashboard-header nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .dashboard-header nav ul li a.logout {
            background-color: #ff416c;
        }

        .dashboard-header nav ul li a.logout:hover {
            background-color: #ff4b2b;
        }

        /* Contenu principal */
        .dashboard-content {
            flex: 1;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Section de bienvenue */
        .welcome-section {
            display: flex;
            align-items: center;
            gap: 40px;
            margin-bottom: 40px;
        }

        .welcome-section img {
            max-width: 300px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .welcome-section .welcome-text {
            flex: 1;
        }

        .welcome-section h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #6a11cb;
        }

        .welcome-section p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.8;
        }

        /* Statistiques */
        .dashboard-stats {
            display: flex;
            justify-content: space-around;
            padding: 40px 20px;
            background-color: #f8f9fa;
            margin-bottom: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat {
            text-align: center;
            padding: 30px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            flex: 1;
            margin: 0 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .stat i {
            font-size: 2.5rem;
            color: #6a11cb;
            margin-bottom: 15px;
        }

        .stat h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 10px;
        }

        .stat p {
            font-size: 1rem;
            color: #666;
        }

        /* Section de recherche */
        .search-section {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .search-section form {
            display: flex;
            gap: 10px;
        }

        .search-section input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .search-section select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .search-section button {
            padding: 10px 20px;
            background-color: #6a11cb;
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-section button:hover {
            background-color: #2575fc;
        }

        /* Résultats de la recherche */
        .search-results {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .search-results h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #6a11cb;
        }

        .search-results table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .search-results th, .search-results td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .search-results th {
            background-color: #6a11cb;
            color: #fff;
            font-weight: 600;
        }

        .search-results tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .search-results tr:hover {
            background-color: #e9ecef;
        }

 /* Section À Propos */
 .about-section {
            background: linear-gradient(135deg, rgba(106, 17, 203, 0.1), rgba(37, 117, 252, 0.1));
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .about-section h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #6a11cb;
        }

        .about-section p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.8;
        }

        .about-features {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .about-features .feature {
            flex: 1;
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .about-features .feature i {
            font-size: 2rem;
            color: #6a11cb;
            margin-bottom: 10px;
        }

        .about-features .feature h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #333;
        }

        .about-features .feature p {
            font-size: 0.9rem;
            color: #666;
        }

        /* Section Contact */
        .contact-section {
            background: linear-gradient(135deg, rgba(106, 17, 203, 0.1), rgba(37, 117, 252, 0.1));
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .contact-section h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #6a11cb;
        }

        .contact-section p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .contact-section form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-section input, .contact-section textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
        }

        .contact-section textarea {
            resize: vertical;
            min-height: 100px;
        }

            .contact-section button {
            padding: 10px 20px;
            background-color: #6a11cb;
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .contact-section button:hover {
            background-color: #2575fc;
        }

        /* Section graphique */
        .chart-section {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .chart-section h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #6a11cb;
        }

        /* Section d'information */
        .info-section {
            display: flex;
            align-items: center;
            gap: 40px;
            margin-bottom: 40px;
        }

        .info-section img {
            max-width: 400px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .info-section .info-text {
            flex: 1;
        }

        .info-section h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #6a11cb;
        }

        .info-section p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.8;
        }

        /* Pied de page */
        .dashboard-footer {
            padding: 20px;
            text-align: center;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            margin-top: auto;
            border-radius: 15px 15px 0 0;
        }

        .dashboard-footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Effets de survol */
        .dashboard-header nav ul li a {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard-header nav ul li a:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Boutons */
        button, .btn {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        button:hover, .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* Tableaux */
        .search-results table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .search-results th, .search-results td {
            padding: 15px;
            border: none;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .search-results th {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
        }

        .search-results tr:hover td {
            background-color: #f8f9fa;
        }

        /* Sections avec fond dégradé */
        .welcome-section, .info-section {
            background: linear-gradient(135deg, rgba(106, 17, 203, 0.1), rgba(37, 117, 252, 0.1));
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Icônes */
        .stat i {
            font-size: 2.5rem;
            color: #6a11cb;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        .stat:hover i {
            transform: scale(1.2);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- En-tête du tableau de bord avec navigation -->
        <header class="dashboard-header">
            <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user']['username']) ?> (Admin)</h1>
            <nav>
                <ul>
                    <li><a href="users.php">Gérer les utilisateurs</a></li>
                    <li><a href="projects.php">Voir les projets</a></li>
                    <li><a href="tasks.php">Gérer les tâches</a></li>
                    <li><a href="reports.php">Gérer les rapports</a></li>
                    <li><a href="../controllers/logout.php" class="logout">Se déconnecter</a></li>
                </ul>
            </nav>
        </header>

        <!-- Contenu principal -->
        <main class="dashboard-content">
            <!-- Section de bienvenue avec image -->
            <section class="welcome-section">
                <div class="welcome-text">
                    <h2>Bienvenue sur MYTAF</h2>
                    <p>
                        MYTAF est votre partenaire pour une gestion de projet fluide et efficace. Que vous soyez un chef de projet, un développeur ou un membre d'équipe, MYTAF vous offre les outils nécessaires pour collaborer, organiser et réussir.
                    </p>
                </div>
            </section>

            <!-- Statistiques -->
            <section class="dashboard-stats">
                <div class="stat">
                    <i class="fas fa-users"></i>
                    <h2><?= count($users) ?></h2>
                    <p>Utilisateurs</p>
                </div>
                <div class="stat">
                    <i class="fas fa-project-diagram"></i>
                    <h2><?= count($projects) ?></h2>
                    <p>Projets</p>
                </div>
                <div class="stat">
                    <i class="fas fa-tasks"></i>
                    <h2><?= count($tasks) ?></h2>
                    <p>Tâches en cours</p>
                </div>
            </section>

            <section class="about-section">
                <h2>À Propos de MYTAF</h2>
                <p>
                    MYTAF est une plateforme de gestion de projets conçue pour les petites et moyennes équipes. Notre mission est de simplifier la collaboration, le suivi des tâches et la gestion des projets pour vous permettre de vous concentrer sur ce qui compte vraiment.
                </p>
                <div class="about-features">
                    <div class="feature">
                        <i class="fas fa-tasks"></i>
                        <h3>Gestion des Tâches</h3>
                        <p>Organisez et suivez vos tâches en temps réel.</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-users"></i>
                        <h3>Collaboration</h3>
                        <p>Travaillez en équipe de manière efficace et transparente.</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-chart-line"></i>
                        <h3>Rapports</h3>
                        <p>Générez des rapports détaillés pour suivre vos progrès.</p>
                    </div>
                </div>
            </section>

            <!-- Résultats de la recherche -->
            <section class="search-results">
                <h2>Projets ayant des tâches</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Projet</th>
                            <th>Utilisateur</th>
                            <th>Tâches</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $projectId => $projectData): ?>
                            <?php $projectName = htmlspecialchars($projectData['projet']['nom_projet']); ?>
                            <?php foreach ($projectData['utilisateurs'] as $userId => $userData): ?>
                                <?php $userName = htmlspecialchars($userData['utilisateur']['username']); ?>
                                <?php foreach ($userData['taches'] as $task): ?>
                                    <tr>
                                        <td><?= $projectName ?></td>
                                        <td><?= $userName ?></td>
                                        <td><?= htmlspecialchars($task['titre']) ?></td>
                                        <td><?= htmlspecialchars($task['statut']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Section Contact -->
            <section class="contact-section">
                <h2>Contactez-nous</h2>
                <p>Vous avez des questions ou besoin d'aide ? Notre équipe est là pour vous.</p>
                <form action="#" method="post">
                    <input type="text" name="name" placeholder="Votre nom" required>
                    <input type="email" name="email" placeholder="Votre email" required>
                    <textarea name="message" placeholder="Votre message" required></textarea>
                    <button type="submit">Envoyer</button>
                </form>
            </section>

            <!-- Section supplémentaire avec image et texte -->
            <section class="info-section">
                <div class="info-text">
                    <h2>Optimisez votre workflow</h2>
                    <p>
                        Avec MYTAF, vous pouvez organiser vos projets en toute simplicité. Assignez des tâches, suivez les délais et collaborez en temps réel avec votre équipe. Notre plateforme est conçue pour vous aider à atteindre vos objectifs plus rapidement.
                    </p>
                </div>
                <img src="gestion-projet.jpg" alt="">
            </section>
        </main>

        <!-- Pied de page -->
        <footer class="dashboard-footer">
            <div class="footer-links">
                <a href="#">À Propos</a>
                <a href="#">Contact</a>
                <a href="#">Politique de confidentialité</a>
            </div>
            <div class="social-media">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
            <p>&copy; 2023 MYTAF. Tous droits réservés.</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('taskChart').getContext('2d');
            const taskChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['En cours', 'À faire', 'Terminé'],
                    datasets: [{
                        label: 'Nombre de Tâches',
                        data: [
                            <?= $taskStatusCount['en_cours'] ?>,
                            <?= $taskStatusCount['a_faire'] ?>,
                            <?= $taskStatusCount['termine'] ?>
                        ],
                        backgroundColor: 'rgba(106, 17, 203, 0.2)',
                        borderColor: '#6a11cb',
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>