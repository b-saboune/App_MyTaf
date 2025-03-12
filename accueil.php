<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYTAF - Gestion de projets et tâches</title>
    <link rel="stylesheet" href="../App_MyTaf/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    color: #333;
    line-height: 1.6;
}

a {
    text-decoration: none;
    color: inherit;
}

/* Header */
header {
    background-color: #2c3e50;
    color: #ecf0f1;
    padding: 20px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.logo {
    font-size: 24px;
    font-weight: bold;
    animation: fadeIn 1s ease-in-out;
}

nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}

nav ul li {
    margin-left: 20px;
}

nav ul li a {
    color: #ecf0f1;
    font-weight: 500;
    transition: color 0.3s ease;
}

nav ul li a:hover {
    color: #3498db;
}

.btn-login {
    background-color: #3498db;
    color: #ecf0f1;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.btn-login:hover {
    background-color: #2980b9;
}

/* Hero Section */
.hero {
    background-color: #3498db;
    color: #ecf0f1;
    text-align: center;
    padding: 100px 20px;
    animation: slideIn 1s ease-in-out;
}

.hero h1 {
    font-size: 48px;
    margin-bottom: 20px;
}

.hero p {
    font-size: 20px;
    margin-bottom: 40px;
}

.btn-primary {
    background-color: #2c3e50;
    color: #ecf0f1;
    padding: 15px 30px;
    border-radius: 5px;
    font-size: 18px;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #34495e;
}

/* Features Section */
.features {
    padding: 60px 20px;
    text-align: center;
    background-color: #ecf0f1;
}

.features h2 {
    font-size: 36px;
    margin-bottom: 40px;
    animation: fadeIn 1s ease-in-out;
}

.feature-cards {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}

.card {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 30%;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
}

.card img {
    max-width: 100%;
    border-radius: 10px;
    margin-bottom: 20px;
}

.card h3 {
    font-size: 24px;
    margin-bottom: 10px;
}

.card p {
    font-size: 16px;
    color: #666;
}

/* Testimonials Section */
.testimonials {
    padding: 60px 20px;
    text-align: center;
    background-color: #fff;
}

.testimonials h2 {
    font-size: 36px;
    margin-bottom: 40px;
    animation: fadeIn 1s ease-in-out;
}

.testimonial-cards {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}

.testimonial {
    background-color: #ecf0f1;
    padding: 20px;
    border-radius: 10px;
    width: 45%;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.testimonial:hover {
    transform: translateY(-10px);
}

.testimonial img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 20px;
}

.testimonial p {
    font-size: 16px;
    color: #666;
    margin-bottom: 10px;
}

.testimonial span {
    font-weight: bold;
    color: #2c3e50;
}

/* Contact Section */
.contact {
    padding: 60px 20px;
    text-align: center;
    background-color: #3498db;
    color: #ecf0f1;
}

.contact h2 {
    font-size: 36px;
    margin-bottom: 40px;
    animation: fadeIn 1s ease-in-out;
}

.contact form {
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.contact input, .contact textarea {
    padding: 10px;
    border-radius: 5px;
    border: none;
    font-size: 16px;
}

.contact textarea {
    height: 150px;
}

.contact button {
    background-color: #2c3e50;
    color: #ecf0f1;
    padding: 15px 30px;
    border-radius: 5px;
    font-size: 18px;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.contact button:hover {
    background-color: #34495e;
}

/* Footer */
footer {
    background-color: #2c3e50;
    color: #ecf0f1;
    padding: 40px 20px;
    text-align: center;
}

.footer-links {
    margin-bottom: 20px;
}

.footer-links a {
    color: #ecf0f1;
    margin: 0 15px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.footer-links a:hover {
    color: #3498db;
}

.contact-info {
    margin-bottom: 20px;
}

.social-media img {
    width: 24px;
    margin: 0 10px;
    transition: opacity 0.3s ease;
}

.social-media a:hover {
    opacity: 0.8;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-container {
        flex-direction: column;
        align-items: flex-start;
    }

    nav ul {
        flex-direction: column;
        margin-top: 10px;
    }

    nav ul li {
        margin-left: 0;
        margin-bottom: 10px;
    }

    .hero h1 {
        font-size: 36px;
    }

    .hero p {
        font-size: 18px;
    }

    .feature-cards, .testimonial-cards {
        flex-direction: column;
    }

    .card, .testimonial {
        width: 100%;
    }
}
      </style>

</head>
<body>
    <!-- En-tête -->
    <header>
        <div class="header-container">
            <div class="logo">MYTAF</div>
            <nav>
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
            <a href="../App_MyTaf/views/login.php" class="btn-login">Connexion</a>
        </div>
    </header>

    <!-- Section principale -->
    <section class="hero">
        <h1>Gérez vos projets et tâches en toute simplicité avec MYTAF</h1>
        <p>Une solution collaborative pour les petites équipes</p>
        <a href="../App_MyTaf/views/register_admin.php" class="btn-primary">Commencer maintenant</a>
    </section>

    <!-- Section des fonctionnalités -->
    <section class="features">
        <h2>Découvrez les fonctionnalités de MYTAF</h2>
        <div class="feature-cards">
            <div class="card">
                <img src="gestion-projet.jpg" alt="Gestion des projets">
                <h3>Gestion des projets</h3>
                <p>Créez, modifiez et suivez vos projets en temps réel.</p>
            </div>
            <div class="card">
                <img src="Gest_tache.png" alt="Gestion des tâches">
                <h3>Gestion des tâches</h3>
                <p>Attribuez et suivez l'état des tâches facilement.</p>
            </div>
            <div class="card">
                <img src="gestion-projet.jpg" alt="Tableau de bord">
                <h3>Tableau de bord</h3>
                <p>Visualisez l'avancement de vos projets avec des indicateurs clairs.</p>
            </div>
        </div>
    </section>

    <!-- Section témoignages -->
    <section class="testimonials">
        <h2>Ce que nos utilisateurs disent</h2>
        <div class="testimonial-cards">
            <div class="testimonial">
                <img src="BR.jpg" alt="Utilisateur 1">
                <p>"MYTAF a révolutionné notre gestion de projets. Simple et efficace !"</p>
                <span>- BRAHIM Fadoul</span>
            </div>
            <div class="testimonial">
                <img src="ND.jpg" alt="Utilisateur 2">
                <p>"L'outil parfait pour les petites équipes. Je recommande !"</p>
                <span>- N'DJANOUNAI </span>
            </div>
        </div>
    </section>

    <!-- Section contact -->
    <section class="contact">
        <h2>Contactez-nous</h2>
        <form>
            <input type="text" placeholder="Votre nom" required>
            <input type="email" placeholder="Votre email" required>
            <textarea placeholder="Votre message" required></textarea>
            <button type="submit" class="btn-primary">Envoyer</button>
        </form>
    </section>

    <!-- Pied de page -->
    <footer>
        
        <div class="contact-info">
            <p>Contact : support@mytaf.com</p>
          </div>    
    </footer>
</body>
</html>