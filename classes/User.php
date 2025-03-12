<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Inscription d'un nouvel utilisateur
    public function register($username, $email, $password) {
        // Vérifier que seul un admin peut ajouter des utilisateurs
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return false; // Refuser l'inscription si l'utilisateur n'est pas un admin
        }
    
        // Rôle par défaut : membre
        $role = 'member';
    
        $query = "INSERT INTO " . $this->table_name . " (username, email, password, role) VALUES (:username, :email, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
    
        return $stmt->execute();
    }
    
    // Authentification
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            die("Utilisateur non trouvé !");
        }
        
        if ($user && password_verify($password, $user['password'])) {
            // Stocker les informations de l'utilisateur dans la session
        $_SESSION['user'] = [
            'id' => $user['idUtilisateurs'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'] // Assurez-vous que le rôle est bien stocké
        ]; 
            return $user;
        } else {
            die("Mot de passe incorrect !");
        }
    }
    

    // Lecture de tous les utilisateurs
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Mise à jour d'un utilisateur
    public function update($id, $username, $email) {
        $query = "UPDATE " . $this->table_name . " SET username = :username, email = :email WHERE idUtilisateurs = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
    
        return $stmt->execute();
    }
    // Suppression d'un utilisateur
    public function delete($id) {
        // Vérifier si l'utilisateur est un admin
        $user = $this->readById($id);
        if ($user && $user['role'] === 'admin') {
            return false; // Empêcher la suppression d'un admin
        }
    
        $query = "DELETE FROM " . $this->table_name . " WHERE idUtilisateurs = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
    
        return $stmt->execute();
    }

    //pour readById($id)
    public function readById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE idUtilisateurs = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>