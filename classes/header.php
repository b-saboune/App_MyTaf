<?php
session_start();
if (isset($_SESSION['user'])) {
    echo '<div class="user-info">';
    echo 'Connecté en tant que : ' . htmlspecialchars($_SESSION['user']['username']);
    echo ' | <a href="../controllers/logout.php">Déconnexion</a>';
    echo '</div>';
}
?>