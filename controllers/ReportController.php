<?php
require_once "../config/Database.php";
require_once "../classes/Report.php";

session_start(); // Démarrez la session au début du script

$database = new Database();
$db = $database->getConnection();
$report = new Report($db);

$upload_dir = '../uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            if (isset($_POST['contenu'])) {
                $contenu = $_POST['contenu'];
                $projet_id = !empty($_POST['projet_id']) ? intval($_POST['projet_id']) : null; // Rendre projet_id optionnel
                $file_path = null;

                // Gestion de l'upload de fichier
                if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                    $allowed_types = [
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/pdf'
                    ];

                    if (in_array($_FILES['file']['type'], $allowed_types)) {
                        $file_name = basename($_FILES['file']['name']);
                        $file_path = $upload_dir . $file_name;

                        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
                            // Le fichier a été uploadé avec succès
                            echo "Fichier uploadé : " . $file_name;
                        } else {
                            $_SESSION['error'] = "Erreur lors de l'upload du fichier.";
                            header("Location: ../views/reports.php");
                            exit();
                        }
                    } else {
                        $_SESSION['error'] = "Type de fichier non autorisé.";
                        header("Location: ../views/reports.php");
                        exit();
                    }
                }

                // Création du rapport
                if ($report->create($contenu, $projet_id, $file_path)) {
                    $_SESSION['message'] = "Rapport créé avec succès.";
                    header("Location: ../views/reports.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Erreur lors de la création du rapport.";
                    header("Location: ../views/reports.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Le contenu est requis pour créer un rapport.";
                header("Location: ../views/reports.php");
                exit();
            }
            break;

        case 'update':
            if (isset($_POST['id'], $_POST['contenu'])) {
                $id = intval($_POST['id']);
                $contenu = $_POST['contenu'];
                $projet_id = !empty($_POST['projet_id']) ? intval($_POST['projet_id']) : null;
                $file_path = null;

                // Gestion de l'upload de fichier (si un nouveau fichier est fourni)
                if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                    $allowed_types = [
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/pdf'
                    ];

                    if (in_array($_FILES['file']['type'], $allowed_types)) {
                        $file_name = basename($_FILES['file']['name']);
                        $file_path = $upload_dir . $file_name;
                        move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
                    } else {
                        $_SESSION['error'] = "Type de fichier non autorisé.";
                        header("Location: ../views/reports.php");
                        exit();
                    }
                }

                if ($report->update($id, $contenu, $projet_id, $file_path)) {
                    $_SESSION['message'] = "Rapport mis à jour avec succès.";
                    header("Location: ../views/reports.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Erreur lors de la mise à jour du rapport.";
                    header("Location: ../views/reports.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Tous les champs sont requis pour mettre à jour un rapport.";
                header("Location: ../views/reports.php");
                exit();
            }
            break;

        case 'delete':
            if (isset($_POST['id'])) {
                $id = intval($_POST['id']);

                if ($report->delete($id)) {
                    $_SESSION['message'] = "Rapport supprimé avec succès.";
                    header("Location: ../views/reports.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression du rapport.";
                    header("Location: ../views/reports.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "ID du rapport requis pour supprimer.";
                header("Location: ../views/reports.php");
                exit();
            }
            break;

        default:
            $_SESSION['error'] = "Action inconnue.";
            header("Location: ../views/reports.php");
            exit();
            break;
    }
} else {
    $_SESSION['error'] = "Aucune action spécifiée.";
    header("Location: ../views/reports.php");
    exit();
}
?>