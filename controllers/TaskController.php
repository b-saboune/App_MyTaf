<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// ...

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(array("message" => "Non autorisé."));
    exit();
}

require_once '../config/Database.php';
require_once '../classes/Task.php';
require_once '../classes/Project.php';

$database = new Database();
$db = $database->getConnection();
$task = new Task($db);
$project = new Project($db);

$request_method = $_SERVER["REQUEST_METHOD"];

// Fonction pour vérifier si l'utilisateur est un administrateur
function isAdmin() {
    return $_SESSION['user']['role'] === 'admin';
}

// Fonction pour vérifier si le projet est assigné à l'utilisateur
function isProjectAssignedToUser($project_id, $user_id) {
    global $project;
    $assignedProjects = $project->readByUser($user_id);
    foreach ($assignedProjects as $assignedProject) {
        if ($assignedProject['idprojet'] == $project_id) {
            return true;
        }
    }
    return false;
}

// Gestion des différentes méthodes HTTP
switch ($request_method) {
    case 'GET':
        // Récupérer les tâches par projet
        if (!empty($_GET["projet_id"])) {
            $projet_id = intval($_GET["projet_id"]);
            $stmt = $task->readByProject($projet_id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $tasks_arr = array();
                $tasks_arr["records"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $task_item = array(
                        "idtaches" => $idtaches,
                        "titre" => htmlspecialchars($titre),
                        "description" => htmlspecialchars($description),
                        "statut" => htmlspecialchars($statut),
                        "date_limite" => $date_limite,
                        "Utilisateurs_id" => $Utilisateurs_id,
                        "projet_id" => $projet_id
                    );
                    array_push($tasks_arr["records"], $task_item);
                }
                http_response_code(200);
                echo json_encode($tasks_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Aucune tâche trouvée."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID du projet manquant."));
        }
        break;

    case 'POST':
        // Récupérer les données du formulaire
        $data = $_POST;

        // Vérifier l'action
        if (isset($data['action']) && $data['action'] === 'create') {
            // Valider les données
            if (!empty($data['titre']) && !empty($data['description']) && !empty($data['statut']) && !empty($data['date_limite']) && !empty($data['projet_id'])) {
                // Vérifier si l'utilisateur est un membre et si le projet lui est assigné
                if (!isAdmin() && !isProjectAssignedToUser($data['projet_id'], $_SESSION['user']['idUtilisateurs'])) {
                    http_response_code(403);
                    echo json_encode(array("message" => "Accès refusé. Ce projet ne vous est pas assigné."));
                    exit();
                }

                $task->titre = htmlspecialchars($data['titre']);
                $task->description = htmlspecialchars($data['description']);
                $task->statut = htmlspecialchars($data['statut']);
                $task->date_limite = $data['date_limite'];
                $task->Utilisateurs_id = $_SESSION['user']['idUtilisateurs']; // Utilisateur connecté
                $task->projet_id = intval($data['projet_id']);

                // Créer la tâche
                if ($task->create()) {
                    // Rediriger vers la liste des tâches avec un message de succès
                    header("Location: ../views/tasks.php?success=1");
                    exit();
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Impossible de créer la tâche."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Données incomplètes."));
            }
        }
        break;

    case 'PUT':
        // Vérifier si l'utilisateur est un administrateur
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(array("message" => "Accès refusé."));
            exit();
        }

        // Récupérer les données JSON
        $data = json_decode(file_get_contents("php://input"), true);

        // Valider les données
        if (!empty($data['idtaches']) && !empty($data['titre']) && !empty($data['description']) && !empty($data['statut']) && !empty($data['date_limite']) && !empty($data['Utilisateurs_id']) && !empty($data['projet_id'])) {
            $task->idtaches = intval($data['idtaches']);
            $task->titre = htmlspecialchars($data['titre']);
            $task->description = htmlspecialchars($data['description']);
            $task->statut = htmlspecialchars($data['statut']);
            $task->date_limite = $data['date_limite'];
            $task->Utilisateurs_id = intval($data['Utilisateurs_id']);
            $task->projet_id = intval($data['projet_id']);

            // Mettre à jour la tâche
            if ($task->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Tâche mise à jour."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de mettre à jour la tâche."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Données incomplètes."));
        }
        break;

    case 'DELETE':
        // Vérifier si l'utilisateur est un administrateur
        if (!isAdmin()) {
            http_response_code(403);
            echo json_encode(array("message" => "Accès refusé."));
            exit();
        }

        // Récupérer les données JSON
        $data = json_decode(file_get_contents("php://input"), true);

        // Valider les données
        if (!empty($data['idtaches'])) {
            $task->idtaches = intval($data['idtaches']);

            // Supprimer la tâche
            if ($task->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Tâche supprimée."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de supprimer la tâche."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID de la tâche manquant."));
        }
        break;

    default:
        // Méthode non autorisée
        http_response_code(405);
        echo json_encode(array("message" => "Méthode non autorisée."));
        break;
}
?>