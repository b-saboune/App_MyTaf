<?php

class Task {
    private $conn;
    private $table = 'taches';

    public $idtaches;
    public $titre;
    public $description;
    public $statut;
    public $date_limite;
    public $Utilisateurs_id;
    public $projet_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer une nouvelle tâche
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET titre = :titre, description = :description, statut = :statut, 
                      date_limite = :date_limite, Utilisateurs_id = :Utilisateurs_id, projet_id = :projet_id";
    
        $stmt = $this->conn->prepare($query);
    
        // Nettoyer les données
        $this->titre = htmlspecialchars(strip_tags($this->titre));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->statut = htmlspecialchars(strip_tags($this->statut));
        $this->date_limite = htmlspecialchars(strip_tags($this->date_limite));
        $this->Utilisateurs_id = htmlspecialchars(strip_tags($this->Utilisateurs_id));
        $this->projet_id = htmlspecialchars(strip_tags($this->projet_id));
    
        // Liaison des paramètres
        $stmt->bindParam(':titre', $this->titre);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':statut', $this->statut);
        $stmt->bindParam(':date_limite', $this->date_limite);
        $stmt->bindParam(':Utilisateurs_id', $this->Utilisateurs_id);
        $stmt->bindParam(':projet_id', $this->projet_id);
    
        // Exécuter la requête
        if ($stmt->execute()) {
            return true;
        } else {
            // Afficher l'erreur SQL pour le débogage
            print_r($stmt->errorInfo());
            return false;
        }
    }

    // Lire toutes les tâches d'un projet
    public function readByProject($projet_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE projet_id = :projet_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':projet_id', $projet_id);
        $stmt->execute();
        return $stmt;
    }

    // Lire toutes les tâches (accessible uniquement pour l'admin)
    public function readAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lire toutes les tâches assignées à un utilisateur (membre)
    public function readByUser($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE Utilisateurs_id = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt;
    }

    
    // Mettre à jour une tâche
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET titre = :titre, description = :description, statut = :statut, 
                      date_limite = :date_limite, Utilisateurs_id = :Utilisateurs_id, projet_id = :projet_id 
                  WHERE idtaches = :idtaches";

        $stmt = $this->conn->prepare($query);

        // Nettoyer les données
        $this->titre = htmlspecialchars(strip_tags($this->titre));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->statut = htmlspecialchars(strip_tags($this->statut));
        $this->date_limite = htmlspecialchars(strip_tags($this->date_limite));
        $this->Utilisateurs_id = htmlspecialchars(strip_tags($this->Utilisateurs_id));
        $this->projet_id = htmlspecialchars(strip_tags($this->projet_id));
        $this->idtaches = htmlspecialchars(strip_tags($this->idtaches));

        // Liaison des paramètres
        $stmt->bindParam(':titre', $this->titre);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':statut', $this->statut);
        $stmt->bindParam(':date_limite', $this->date_limite);
        $stmt->bindParam(':Utilisateurs_id', $this->Utilisateurs_id);
        $stmt->bindParam(':projet_id', $this->projet_id);
        $stmt->bindParam(':idtaches', $this->idtaches);

        return $stmt->execute();
    }

    // Supprimer une tâche
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE idtaches = :idtaches";
        $stmt = $this->conn->prepare($query);

        // Nettoyer les données
        $this->idtaches = htmlspecialchars(strip_tags($this->idtaches));

        // Liaison des paramètres
        $stmt->bindParam(':idtaches', $this->idtaches);

        return $stmt->execute();
    }

    // Lire une tâche par son ID
    public function readById() {
        $query = "SELECT * FROM " . $this->table . " WHERE idtaches = :idtaches";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idtaches', $this->idtaches);
        $stmt->execute();
        return $stmt;
    }
}