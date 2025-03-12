<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Admin</title>
    <link rel="stylesheet" href="../App_MyTaf/assets/css/style.css">
</head>

<style>    /* style.css */
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
        <h1>Inscription Admin</h1>
        <form action="../controllers/UserController.php" method="POST">
            <input type="hidden" name="action" value="register_admin">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">S'inscrire</button>
        </form>
    </div>
</body>
</html>