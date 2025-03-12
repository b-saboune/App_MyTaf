<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Afficher un message de succès après l'inscription ou la connexion
if (isset($_GET['message'])) {
    echo '<p class="success-message">' . htmlspecialchars($_GET['message']) . '</p>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<style>
     /* style.css */
body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background: rgba(255, 255, 255, 0.1);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    width: 100%;
    max-width: 400px;
}

h1, h2 {
    color: #ffffff;
    text-align: center;
}

p {
    text-align: center;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 0.5rem;
    color: #ddd;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    padding: 10px;
    margin-bottom: 1rem;
    border: none;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

input[type="text"]::placeholder,
input[type="email"]::placeholder,
input[type="password"]::placeholder {
    color: #ccc;
}

button {
    background-color: #2575fc;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #1b5bbf;
}

a {
    color: #2575fc;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.success-message {
    color: #4CAF50;
    text-align: center;
    margin-bottom: 1rem;
}
</style>
<body>
    <div class="container">
        <h1>Connexion</h1>
        <h2>Bienvenue sur MYTAF</h2>
        <p>Veuillez vous connecter pour accéder à votre espace personnel.</p>
        <form action="../controllers/UserController.php" method="POST">
            <input type="hidden" name="action" value="login">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore de compte ? <a href="register_admin.php">Inscrivez-vous</a></p>
    </div>
</body>
</html>