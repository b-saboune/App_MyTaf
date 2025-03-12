<?php
class Report {
    private $conn;
    private $table = "rapport";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($contenu, $projet_id = null, $file_path = null) {
        try {
            $query = "INSERT INTO " . $this->table . " (contenu, projet_id, file_path, date_creation)
                      VALUES (:contenu, :projet_id, :file_path, NOW())";
            $stmt = $this->conn->prepare($query);
    
            $stmt->bindParam(":contenu", $contenu);
            $stmt->bindParam(":projet_id", $projet_id);
            $stmt->bindParam(":file_path", $file_path);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la création du rapport : " . $e->getMessage();
            return false;
        }
    }

    public function readByProject($projet_id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE projet_id = :projet_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':projet_id', $projet_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture des rapports : " . $e->getMessage();
            return [];
        }
    }

    public function readAll() {
        try {
            $query = "SELECT * FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture des rapports : " . $e->getMessage();
            return [];
        }
    }

    public function readById($id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE idrapport = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération du rapport : " . $e->getMessage();
            return null;
        }
    }

    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE idrapport = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du rapport : " . $e->getMessage();
            return false;
        }
    }

    public function update($id, $contenu, $projet_id = null, $file_path = null) {
    try {
        $query = "UPDATE " . $this->table . " SET 
                  contenu = :contenu, 
                  projet_id = :projet_id, 
                  file_path = :file_path
                  WHERE idrapport = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":contenu", $contenu);
        $stmt->bindParam(":projet_id", $projet_id);
        $stmt->bindParam(":file_path", $file_path);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour du rapport : " . $e->getMessage();
        return false;
    }
}

    public function readAllWithProjectName() {
        try {
            $query = "SELECT r.*, p.nom_projet 
                      FROM " . $this->table . " r 
                      LEFT JOIN projet p ON r.projet_id = p.idprojet";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture des rapports : " . $e->getMessage();
            return [];
        }
    }
}
?>
