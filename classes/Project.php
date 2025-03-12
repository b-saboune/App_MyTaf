<?php
class Project {
    private $conn;
    private $table = "projet";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($nom, $description, $date_debut, $date_fin, $statut) {
        // Validation des données
        if (empty($nom) || empty($description) || empty($date_debut) || empty($date_fin) || empty($statut)) {
            throw new InvalidArgumentException("Tous les champs sont obligatoires.");
        }

        if (!strtotime($date_debut) || !strtotime($date_fin)) {
            throw new InvalidArgumentException("Les dates doivent être au format valide.");
        }

        try {
            $query = "INSERT INTO " . $this->table . " (nom_projet, description, date_debut, date_fin, statut)
                      VALUES (:nom, :description, :date_debut, :date_fin, :statut)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":nom", $nom);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":date_debut", $date_debut);
            $stmt->bindParam(":date_fin", $date_fin);
            $stmt->bindParam(":statut", $statut);

            if ($stmt->execute()) {
                return $this->conn->lastInsertId(); // Retourne l'ID du projet créé
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la création du projet : " . $e->getMessage());
            return false;
        }
    }

    
    public function read() {
        try {
            $query = "SELECT * FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la lecture des projets : " . $e->getMessage());
            return [];
        }
    }

    public function readById($id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE idprojet = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la lecture du projet par ID : " . $e->getMessage());
            return null;
        }
    }

    
    public function update($id, $nom, $description, $date_debut, $date_fin, $statut) {
        try {
            $query = "UPDATE " . $this->table . " SET 
                      nom_projet = :nom, 
                      description = :description, 
                      date_debut = :date_debut, 
                      date_fin = :date_fin, 
                      statut = :statut
                      WHERE idprojet = :id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":nom", $nom);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":date_debut", $date_debut);
            $stmt->bindParam(":date_fin", $date_fin);
            $stmt->bindParam(":statut", $statut);
            $stmt->bindParam(":id", $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du projet : " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE idprojet = :id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id", $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du projet : " . $e->getMessage());
            return false;
        }
    }

    public function readByUser($userId) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE Utilisateurs_id = :userId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la lecture des projets par utilisateur : " . $e->getMessage());
            return [];
        }
    }

     // Méthode pour récupérer tous les projets
     public function readAll() {
        $query = "SELECT * FROM projet";
        $stmt = $this->conn->prepare($query);        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>